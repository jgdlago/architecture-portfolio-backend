<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $setting = DB::table('site_settings')
            ->where('key', 'contact')
            ->first(['id', 'value']);

        if (! $setting) {
            return;
        }

        $value = is_array($setting->value)
            ? $setting->value
            : json_decode((string) $setting->value, true);

        if (! is_array($value) || ! array_key_exists('whatsapp_url', $value)) {
            return;
        }

        unset($value['whatsapp_url']);

        DB::table('site_settings')
            ->where('id', $setting->id)
            ->update([
                'value' => json_encode($value, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed: removed deprecated key from JSON payload.
    }
};
