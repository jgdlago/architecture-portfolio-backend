<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('allows updating featured projects setting with valid payload', function () {
    $admin = User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true]);
    Sanctum::actingAs($admin);

    $this->postJson('/api/admin/site-settings/upsert', [
        'key' => 'featured_projects',
        'value' => [
            'title' => 'Projetos em Destaque',
            'description' => 'Texto atualizado pelo painel.',
        ],
    ])->assertOk()->assertJsonPath('key', 'featured_projects');
});

it('rejects unknown setting key', function () {
    $admin = User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true]);
    Sanctum::actingAs($admin);

    $this->postJson('/api/admin/site-settings/upsert', [
        'key' => 'unknown_key',
        'value' => ['foo' => 'bar'],
    ])->assertUnprocessable()->assertJsonValidationErrors('key');
});

it('rejects invalid seo payload', function () {
    $admin = User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true]);
    Sanctum::actingAs($admin);

    $this->postJson('/api/admin/site-settings/upsert', [
        'key' => 'seo',
        'value' => [
            'title' => '',
            'description' => '',
        ],
    ])->assertUnprocessable();
});

it('forbids non admin from updating site settings', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    Sanctum::actingAs($user);

    $this->postJson('/api/admin/site-settings/upsert', [
        'key' => 'seo',
        'value' => [
            'title' => 'Portfolio',
            'description' => 'Descricao',
        ],
    ])->assertForbidden();
});
