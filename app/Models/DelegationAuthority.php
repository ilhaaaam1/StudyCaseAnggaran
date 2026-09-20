<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelegationAuthority extends Model
{
    use HasFactory;

    protected $table = 'delegation_authorities';

    protected $fillable = [
        'user_id',
        'delegate_to_user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id_pengguna');
    }

    public function delegateTo(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'delegate_to_user_id', 'id_pengguna');
    }
}
