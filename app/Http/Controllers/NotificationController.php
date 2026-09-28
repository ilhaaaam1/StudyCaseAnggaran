<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi sebagai terbaca dan redirect ke URL tujuan.
     */
    public function read(Request $request, $id)
    {
        // PRESENTASI: Logika Mark as Read
        // 1. Cari notifikasi milik user yang sedang login berdasarkan ID notifikasi (UUID)
        $notification = Auth::user()->notifications()->findOrFail($id);

        // 2. Tandai sebagai telah dibaca
        $notification->markAsRead();

        // 3. Ambil URL tujuan dari data notifikasi (jika ada), atau redirect ke halaman default
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($url);
    }
}
