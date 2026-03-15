<?php

use Database\Seeders\PortfolioCatalogSeeder;
use Database\Seeders\SiteSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns featured projects on home payload with data envelope', function () {
    $this->seed([
        SiteSettingsSeeder::class,
        PortfolioCatalogSeeder::class,
    ]);

    $response = $this->getJson('/api/home');

    $response
        ->assertOk()
        ->assertJsonPath('featured_projects.data.0.slug', 'residencia-dal-lago')
        ->assertJsonPath('featured_projects.data.0.is_featured', true)
        ->assertJsonPath('settings.hero.title', 'Arquitetura como narrativa espacial')
        ->assertJsonPath('settings.featured_projects.title', 'Projetos Selecionados')
        ->assertJsonPath('settings.footer_services.title', 'Serviços')
        ->assertJsonPath('settings.seo.title', 'Iara Tedesco | Arquitetura & Urbanismo')
        ->assertJsonPath('settings.navbar.brand_name', 'Iara Tedesco');
});

it('loads project detail by slug from public listing', function () {
    $this->seed([
        PortfolioCatalogSeeder::class,
    ]);

    $listing = $this->getJson('/api/projects')
        ->assertOk()
        ->json('data');

    expect($listing)->not->toBeEmpty();

    $slug = $listing[0]['slug'] ?? null;

    expect($slug)->not->toBeNull();

    $this->getJson("/api/projects/{$slug}")
        ->assertOk()
        ->assertJsonPath('data.slug', $slug)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'description',
                'category',
                'images',
            ],
        ]);
});
