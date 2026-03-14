<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('cover_image_path')->nullable();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->decimal('area_m2', 8, 2)->nullable();
            $table->json('additional_info')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_featured', 'published_at']);
            $table->index(['project_category_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
