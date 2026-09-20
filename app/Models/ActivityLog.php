<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id_pengguna');
    }

    /**
     * Helper untuk mencatat log dengan mudah
     */
    public static function log(string $activity): void
    {
        self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'ip_address' => request()->ip(),
        ]);
    }
}
