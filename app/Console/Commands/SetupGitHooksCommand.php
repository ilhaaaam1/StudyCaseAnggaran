<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupGitHooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:setup-hooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pasang dan aktifkan Git Hooks (post-merge auto sync) untuk lingkungan kerja tim';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $gitDir = base_path('.git');
        $githooksDir = base_path('.githooks');

        if (! File::isDirectory($gitDir)) {
            $this->warn('Direktori .git tidak ditemukan. Lewati pemasangan Git Hooks (lingkungan non-git/production container).');

            return Command::SUCCESS;
        }

        if (! File::isDirectory($githooksDir)) {
            $this->error('Direktori sumber .githooks tidak ditemukan.');

            return Command::FAILURE;
        }

        $this->info('Mengonfigurasi Git Hooks untuk tim SIRAB...');

        // 1. Set core.hooksPath ke folder .githooks (Standar Git modern)
        $process = exec('git config core.hooksPath .githooks 2>&1', $output, $returnCode);
        if ($returnCode === 0) {
            $this->line(' <info>✔</info> Berhasil menyetel <comment>git config core.hooksPath .githooks</comment>');
        } else {
            $this->warn(' Gagal menyetel core.hooksPath via git config. Beralih ke penyalinan manual ke .git/hooks...');
        }

        // 2. Dual-fallback: Salin file hook langsung ke .git/hooks
        $gitHooksTargetDir = base_path('.git/hooks');
        if (! File::isDirectory($gitHooksTargetDir)) {
            File::makeDirectory($gitHooksTargetDir, 0755, true);
        }

        $files = File::files($githooksDir);
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $targetPath = $gitHooksTargetDir.DIRECTORY_SEPARATOR.$filename;

            File::copy($file->getRealPath(), $targetPath);

            // Berikan izin eksekusi jika pada OS berbasis Unix/Linux/macOS
            if (DIRECTORY_SEPARATOR === '/') {
                @chmod($targetPath, 0755);
                @chmod($file->getRealPath(), 0755);
            }

            $this->line(" <info>✔</info> Hook <comment>{$filename}</comment> terpasang di <comment>.git/hooks/{$filename}</comment>");
        }

        $this->newLine();
        $this->info('Git Hooks berhasil diaktifkan! Setiap kali Anda melakukan git pull, migrasi dan dependensi akan disinkronkan otomatis.');

        return Command::SUCCESS;
    }
}
