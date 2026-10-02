<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Mengambil nilai pengaturan berdasarkan key.
     */
    public static function getSetting(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    /**
     * Memperbarui atau membuat pengaturan baru.
     */
    public static function setSetting(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
