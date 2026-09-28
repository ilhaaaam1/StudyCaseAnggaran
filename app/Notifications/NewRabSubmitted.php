<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        // PRESENTASI: Format data notifikasi yang akan disimpan di database
        return [
            'title' => 'Pengajuan RAB Baru',
            'message' => 'RAB "' . $this->pengajuanRab->judul . '" menunggu proses verifikasi.',
            'url' => route('finance.show', $this->pengajuanRab->id_pengajuan),
            'id_pengajuan' => $this->pengajuanRab->id_pengajuan
        ];
    }
}
