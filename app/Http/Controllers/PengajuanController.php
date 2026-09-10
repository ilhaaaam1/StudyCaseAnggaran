<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RabPriority;
use App\Enums\RabStatus;
use App\Enums\UserRole;
use App\Models\Rab;
use App\Models\RabAttachment;
use App\Models\RabItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * Display the budget submission form.
     */
    public function index(Request $request): View
    {
        $divisions = [
            'Teknologi Informasi',
            'Keuangan',
            'Marketing',
            'HRD',
            'Umum & Fasilitas',
            'Logistik',
            'Administrasi',
        ];

        $periods = [
            'Q1 '.date('Y'),
            'Q2 '.date('Y'),
            'Q3 '.date('Y'),
            'Q4 '.date('Y'),
        ];

        return view('pengajuan.index', compact('divisions', 'periods'));
    }

    /**
     * Store a newly created budget submission (RAB).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:100',
            'period' => 'required|string|max:50',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'justification' => 'nullable|string|max:2000',
            'action_type' => 'required|in:submit,draft',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.unit' => 'required|string|max:50',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // 1. Generate nomor kode unik RAB (format: RAB-YYYY-XXX)
        $year = date('Y');
        $lastRab = Rab::where('code', 'like', "RAB-{$year}-%")->latest('id')->first();
        $nextNumber = 1;
        if ($lastRab && preg_match("/RAB-{$year}-(\d+)/", $lastRab->code, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }
        $code = sprintf('RAB-%s-%03d', $year, $nextNumber);

        // 2. Tentukan user ID dan status
        $userId = Auth::id() ?? User::where('role', UserRole::USER)->value('id') ?? 1;
        $status = $validated['action_type'] === 'submit' ? RabStatus::DIAJUKAN : RabStatus::DRAFT;

        // 3. Hitung estimasi total dari rincian item
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += (float) $item['quantity'] * (float) $item['unit_price'];
        }

        // 4. Simpan Record RAB
        $rab = Rab::create([
            'user_id' => $userId,
            'code' => $code,
            'title' => $validated['title'],
            'division' => $validated['division'],
            'period' => $validated['period'],
            'priority' => RabPriority::from($validated['priority']),
            'total_amount' => $totalAmount,
            'justification' => $validated['justification'] ?? null,
            'status' => $status,
        ]);

        // 5. Simpan Rincian Items
        foreach ($validated['items'] as $itemData) {
            $qty = (int) $itemData['quantity'];
            $price = (float) $itemData['unit_price'];
            RabItem::create([
                'rab_id' => $rab->id,
                'description' => $itemData['description'],
                'unit' => $itemData['unit'],
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $qty * $price,
            ]);
        }

        // 6. Simpan File Dokumen Pendukung
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $origName = $file->getClientOriginalName();
                    $path = $file->store('attachments', 'public');
                    $size = round($file->getSize() / 1024).' KB';
                    $ext = $file->getClientOriginalExtension();

                    RabAttachment::create([
                        'rab_id' => $rab->id,
                        'file_name' => $origName,
                        'file_path' => $path,
                        'file_size' => $size,
                        'file_type' => $ext,
                    ]);
                }
            }
        }

        $message = $status === RabStatus::DIAJUKAN
            ? "Pengajuan RAB ({$code}) berhasil dikirim untuk ditinjau Admin!"
            : "Pengajuan RAB ({$code}) berhasil disimpan sebagai Draft.";

        return redirect()->route('rab.index')->with('success', $message);
    }
}
