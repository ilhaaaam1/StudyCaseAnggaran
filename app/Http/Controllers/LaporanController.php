<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RabStatus;
use App\Models\Rab;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display the report page with budget realization analytics.
     */
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', date('Y'));

        // Query berdasarkan tahun
        $query = Rab::whereYear('created_at', $year);

        $totalCount = (clone $query)->count();
        $totalAmount = (float) (clone $query)->sum('total_amount');

        $approvedCount = (clone $query)->where('status', RabStatus::DISETUJUI)->count();
        $approvedAmount = (float) (clone $query)->where('status', RabStatus::DISETUJUI)->sum('total_amount');

        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0.0;
        $approvalPercentage = $totalAmount > 0 ? round(($approvedAmount / $totalAmount) * 100, 1) : 0.0;

        $processedCount = (clone $query)->whereIn('status', [RabStatus::DISETUJUI, RabStatus::DITOLAK, RabStatus::REVISI])->count();
        $approvalRate = $processedCount > 0 ? round(($approvedCount / $processedCount) * 100, 1) : 0.0;

        // Breakdown per Divisi
        $divisionReports = (clone $query)->select(
            'division',
            DB::raw('COUNT(*) as total_count'),
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('SUM(CASE WHEN status = "disetujui" THEN total_amount ELSE 0 END) as approved_amount')
        )
            ->groupBy('division')
            ->orderByDesc('total_amount')
            ->get()
            ->map(function ($item) {
                $tot = (float) $item->total_amount;
                $app = (float) $item->approved_amount;
                $rem = max(0, $tot - $app);
                $pct = $tot > 0 ? round(($app / $tot) * 100) : 0;

                return [
                    'division' => $item->division,
                    'count' => $item->total_count,
                    'total_amount' => $tot,
                    'approved_amount' => $app,
                    'remaining_amount' => $rem,
                    'percentage' => (int) $pct,
                ];
            });

        // Seluruh dokumen RAB untuk tabel rekapitulasi laporan
        $rabs = (clone $query)->with(['user', 'approver'])
            ->latest('created_at')
            ->get();

        return view('laporan.index', compact(
            'year',
            'totalCount',
            'totalAmount',
            'approvedCount',
            'approvedAmount',
            'averageAmount',
            'approvalPercentage',
            'processedCount',
            'approvalRate',
            'divisionReports',
            'rabs'
        ));
    }
}
