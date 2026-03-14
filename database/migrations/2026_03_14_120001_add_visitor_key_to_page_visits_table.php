<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->string('visitor_key', 64)->nullable()->after('referer');
            $table->index(['visitor_key', 'path', 'created_at'], 'page_visits_visitor_path_created_idx');
            $table->index(['visitor_key', 'created_at'], 'page_visits_visitor_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table): void {
            $table->dropIndex('page_visits_visitor_path_created_idx');
            $table->dropIndex('page_visits_visitor_created_idx');
            $table->dropColumn('visitor_key');
        });
    }
};
