<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RabStatus;
use App\Models\Rab;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with dynamic analytics.
     */
    public function index(Request $request): View
    {
        // 1. Ringkasan Statistik Kartu Atas
        $totalCount = Rab::count();
        $totalAmount = (float) Rab::sum('total_amount');

        $approvedCount = Rab::where('status', RabStatus::DISETUJUI)->count();
        $approvedAmount = (float) Rab::where('status', RabStatus::DISETUJUI)->sum('total_amount');

        $pendingCount = Rab::where('status', RabStatus::DIAJUKAN)->count();
        $pendingAmount = (float) Rab::where('status', RabStatus::DIAJUKAN)->sum('total_amount');

        $rejectedCount = Rab::whereIn('status', [RabStatus::DITOLAK, RabStatus::REVISI])->count();
        $rejectedAmount = (float) Rab::whereIn('status', [RabStatus::DITOLAK, RabStatus::REVISI])->sum('total_amount');

        // 2. Pengajuan Terbaru (5 data terakhir)
        $recentRabs = Rab::with('user')
            ->latest('created_at')
            ->take(5)
            ->get();

        // 3. Ringkasan per Divisi / Unit Kerja
        $divisionStats = Rab::select(
            'division',
            DB::raw('COUNT(*) as total_count'),
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('SUM(CASE WHEN status = "disetujui" THEN total_amount ELSE 0 END) as approved_amount'),
            DB::raw('SUM(CASE WHEN status = "diajukan" THEN total_amount ELSE 0 END) as pending_amount')
        )
            ->groupBy('division')
            ->orderByDesc('total_amount')
            ->get()
            ->map(function ($row) {
                $total = (float) $row->total_amount;
                $approved = (float) $row->approved_amount;
                $percentage = $total > 0 ? round(($approved / $total) * 100) : 0;

                return [
                    'division' => $row->division,
                    'count' => $row->total_count,
                    'total_amount' => $total,
                    'approved_amount' => $approved,
                    'pending_amount' => (float) $row->pending_amount,
                    'percentage' => (int) $percentage,
                ];
            });

        return view('dashboard', compact(
            'totalCount',
            'totalAmount',
            'approvedCount',
            'approvedAmount',
            'pendingCount',
            'pendingAmount',
            'rejectedCount',
            'rejectedAmount',
            'recentRabs',
            'divisionStats'
        ));
    }
}
