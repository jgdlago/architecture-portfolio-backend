<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class PortfolioCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $residential = ProjectCategory::query()->updateOrCreate(
            ['slug' => 'residencial'],
            ['name' => 'Residencial', 'sort_order' => 1, 'is_active' => true],
        );

        Project::query()->updateOrCreate(
            ['slug' => 'residencia-dal-lago'],
            [
                'title' => 'Residencia Dal Lago',
                'short_description' => 'Projeto residencial contemporaneo com foco em iluminacao natural.',
                'description' => 'Projeto arquitetonico residencial com linguagem minimalista e integracao ao entorno.',
                'project_category_id' => $residential->id,
                'cover_image_path' => null,
                'location' => 'Passo Fundo, RS',
                'year' => 2025,
                'is_featured' => true,
                'sort_order' => 1,
                'published_at' => now(),
            ],
        );
    }
}
