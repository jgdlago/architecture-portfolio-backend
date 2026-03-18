<?php

use Database\Seeders\SiteSettingsSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensures default site settings are present when deploying with migrate in PRD.
        app(SiteSettingsSeeder::class)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback: this migration only applies default data.
    }
};
