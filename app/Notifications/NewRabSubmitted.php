<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRabSubmitted extends Notification
{
    use Queueable;

    public $pengajuanRab;

    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuanRab)
    {
        $this->pengajuanRab = $pengajuanRab;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // PRESENTASI: Menentukan channel notifikasi ke database
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->pengajuanRab->loadMissing('pengguna');

        $noRab = $this->pengajuanRab->no_rab;
        $judul = $this->pengajuanRab->judul_pengajuan;
        $nominal = 'Rp '.number_format((float) $this->pengajuanRab->estimasi_total, 0, ',', '.');
        $pemohon = $this->pengajuanRab->pengguna->nama_lengkap ?? 'Staf';

        // PRESENTASI: Format data notifikasi yang lebih spesifik dan informatif (Perbaikan atribut judul_pengajuan)
        return [
            'title' => 'Pengajuan RAB Baru',
            'message' => "RAB {$noRab} ({$judul}) senilai {$nominal} telah diajukan oleh {$pemohon} dan menunggu proses verifikasi Anda.",
            'url' => route('finance.show', $this->pengajuanRab->id_pengajuan),
            'id_pengajuan' => $this->pengajuanRab->id_pengajuan,
        ];
    }
}
