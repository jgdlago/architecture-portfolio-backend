<?php

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('keeps only one cover image when selecting a project image as cover', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    Sanctum::actingAs($admin);

    $project = Project::create([
        'title' => 'Projeto Teste',
        'slug' => 'projeto-teste',
    ]);

    $first = ProjectImage::create([
        'project_id' => $project->id,
        'image_path' => 'projects/first.jpg',
        'is_cover' => false,
        'sort_order' => 0,
    ]);

    $second = ProjectImage::create([
        'project_id' => $project->id,
        'image_path' => 'projects/second.jpg',
        'is_cover' => false,
        'sort_order' => 1,
    ]);

    $this->putJson("/api/admin/projects/{$project->id}/images/{$second->id}", [
        'is_cover' => true,
    ])->assertOk();

    expect($first->fresh()->is_cover)->toBeFalse();
    expect($second->fresh()->is_cover)->toBeTrue();
    expect($project->fresh()->cover_image_path)->toBe('projects/second.jpg');
});
