<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DevSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:setup
                            {--seed-dummy : Otomatis jalankan DummyDataSeeder untuk data pengujian}
                            {--fresh : Jalankan migrate:fresh sebelum seeding (PERHATIAN: menghapus semua data lokal)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Satu perintah setup lengkap lingkungan lokal pengembang (env, key, git hooks, migrasi, dan seed)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('==================================================================');
        $this->info('  SIRAB SDN Sidokare 3 - Onboarding & Environment Setup Otomatis  ');
        $this->info('==================================================================');
        $this->newLine();

        // 1. Salin .env jika belum ada
        if (! File::exists(base_path('.env'))) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), base_path('.env'));
                $this->info('✔ File .env berhasil dibuat dari .env.example.');
            } else {
                $this->warn('⚠ File .env.example tidak ditemukan. Lewati salin .env.');
            }
        } else {
            $this->line('✔ File .env sudah ada.');
        }

        // 2. Generate APP_KEY jika belum ada
        if (empty(env('APP_KEY'))) {
            $this->call('key:generate');
            $this->info('✔ APP_KEY berhasil dibuat.');
        }

        // 3. Pasang Git Hooks
        $this->call('dev:setup-hooks');
        $this->newLine();

        // 4. Migrasi Database
        if ($this->option('fresh')) {
            $this->warn('Menjalankan migrate:fresh...');
            $this->call('migrate:fresh', ['--force' => true]);
        } else {
            $this->info('Menjalankan migrasi database...');
            $this->call('migrate', ['--force' => true]);
        }

        // 5. Seeding Master Data Idempoten
        $this->info('Menjalankan seeding master data esensial...');
        $this->call('db:seed', ['--force' => true]);

        // 6. Seeding Data Dummy (Opsional)
        if ($this->option('seed-dummy') || $this->confirm('Apakah Anda ingin menambahkan data pengajuan dummy (RAB-001 s/d 004) untuk pengujian lokal?', false)) {
            $this->info('Menyuntikkan data dummy pengujian...');
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\DummyDataSeeder',
                '--force' => true,
            ]);
            $this->info('✔ Data dummy pengujian siap digunakan.');
        }

        $this->newLine();
        $this->info('==================================================================');
        $this->info('  SETUP SELESAI! Lingkungan kerja lokal Anda sudah 100% siap.    ');
        $this->info('  Untuk rutinitas harian, cukup jalankan: git pull origin main     ');
        $this->info('==================================================================');

        return Command::SUCCESS;
    }
}
