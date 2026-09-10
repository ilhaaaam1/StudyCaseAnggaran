<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'rab_id',
    'file_name',
    'file_path',
    'file_size',
    'file_type',
])]
class RabAttachment extends Model
{
    use HasFactory;

    /**
     * Relasi ke parent RAB.
     */
    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }
}
