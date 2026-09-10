<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RabPriority;
use App\Enums\RabStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'code',
    'title',
    'division',
    'period',
    'priority',
    'total_amount',
    'justification',
    'status',
    'admin_note',
    'approved_by',
    'approved_at',
])]
class Rab extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'priority' => RabPriority::class,
            'status' => RabStatus::class,
            'total_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * User yang mengajukan RAB.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Admin/Atasan yang mereviu dan menyetujui/menolak RAB.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Rincian item-item biaya dalam RAB.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RabItem::class, 'rab_id');
    }

    /**
     * File lampiran dokumen pendukung RAB.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(RabAttachment::class, 'rab_id');
    }

    /**
     * Helper untuk hitung total dari relasi items.
     */
    public function recalculateTotal(): self
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();

        return $this;
    }
}
