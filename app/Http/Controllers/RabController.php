<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RabStatus;
use App\Models\Rab;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RabController extends Controller
{
    /**
     * Display the list of RABs with search, filter, and pagination.
     */
    public function index(Request $request): View
    {
        $query = Rab::with(['user', 'items']);

        // 1. Pencarian (Nomor RAB, Judul, Divisi)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status
        $status = $request->input('status');
        if ($status && $status !== 'all' && RabStatus::tryFrom($status)) {
            $query->where('status', $status);
        }

        // 3. Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest('created_at');
        } else {
            $query->latest('created_at');
        }

        // Hitung Grand Total dari query sebelum pagination
        $grandTotal = (float) (clone $query)->sum('total_amount');

        // Pagination
        $rabs = $query->paginate(10)->withQueryString();

        return view('rab.index', compact('rabs', 'grandTotal', 'status', 'search', 'sort'));
    }
}
