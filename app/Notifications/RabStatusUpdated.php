<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RabStatusUpdated extends Notification
{
    use Queueable;

    public $pengajuanRab;

    public $messageStr;

    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuanRab, $messageStr, $url)
    {
        $this->pengajuanRab = $pengajuanRab;
        $this->messageStr = $messageStr;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Update Status RAB',
            'message' => $this->messageStr,
            'url' => $this->url,
            'id_pengajuan' => $this->pengajuanRab->id_pengajuan,
        ];
    }
}
