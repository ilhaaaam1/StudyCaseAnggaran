<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RabStatus;
use App\Enums\UserRole;
use App\Models\Rab;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanController extends Controller
{
    /**
     * Display the approval queue list.
     */
    public function index(Request $request): View
    {
        // 1. RAB yang memerlukan review / persetujuan
        $pendingRabs = Rab::with(['user', 'items', 'attachments'])
            ->where('status', RabStatus::DIAJUKAN)
            ->latest('created_at')
            ->get();

        // 2. Statistik
        $pendingCount = Rab::where('status', RabStatus::DIAJUKAN)->count();
        $approvedCount = Rab::where('status', RabStatus::DISETUJUI)->count();
        $rejectedCount = Rab::whereIn('status', [RabStatus::DITOLAK, RabStatus::REVISI])->count();

        // 3. Riwayat review terakhir
        $historyRabs = Rab::with(['user', 'approver'])
            ->whereIn('status', [RabStatus::DISETUJUI, RabStatus::DITOLAK, RabStatus::REVISI])
            ->whereNotNull('approved_at')
            ->latest('approved_at')
            ->take(5)
            ->get();

        return view('persetujuan.index', compact(
            'pendingRabs',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'historyRabs'
        ));
    }

    /**
     * Update approval status for a specific RAB.
     */
    public function updateStatus(Request $request, Rab $rab): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject,revision',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $approverId = Auth::id() ?? User::where('role', UserRole::ADMIN)->value('id');

        switch ($validated['action']) {
            case 'approve':
                $rab->status = RabStatus::DISETUJUI;
                $message = "Pengajuan {$rab->code} berhasil disetujui!";
                break;
            case 'reject':
                $rab->status = RabStatus::DITOLAK;
                $message = "Pengajuan {$rab->code} telah ditolak.";
                break;
            case 'revision':
                $rab->status = RabStatus::REVISI;
                $message = "Pengajuan {$rab->code} dikembalikan untuk revisi.";
                break;
        }

        $rab->admin_note = $validated['admin_note'] ?? null;
        $rab->approved_by = $approverId;
        $rab->approved_at = now();
        $rab->save();

        return redirect()->route('persetujuan.index')->with('success', $message);
    }
}
