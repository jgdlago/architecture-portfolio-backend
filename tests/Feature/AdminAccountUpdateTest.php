<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.admin_email' => 'admin@example.com']);
});

it('allows admin to update own account data', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
        'name' => 'Admin Original',
        'cau' => null,
    ]);

    Sanctum::actingAs($admin);

    $response = $this->putJson('/api/admin/account', [
        'name' => 'Admin Atualizado',
        'email' => 'admin@example.com',
        'cau' => 'A12345',
    ]);

    $response->assertOk()
        ->assertJsonPath('user.name', 'Admin Atualizado')
        ->assertJsonPath('user.email', 'admin@example.com')
        ->assertJsonPath('user.cau', 'A12345');

    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
        'name' => 'Admin Atualizado',
        'email' => 'admin@example.com',
        'cau' => 'A12345',
    ]);
});

it('requires current password when email changes', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/admin/account', [
        'name' => 'Admin',
        'email' => 'new-admin@example.com',
    ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
});

it('rejects invalid current password when email changes', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/admin/account', [
        'name' => 'Admin',
        'email' => 'new-admin@example.com',
        'current_password' => 'wrong-pass',
    ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
});

it('prevents duplicate email on account update', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/admin/account', [
        'name' => 'Admin',
        'email' => 'existing@example.com',
        'current_password' => 'secret-123',
    ])->assertUnprocessable()->assertJsonValidationErrors('email');
});

it('allows admin to update own password', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/admin/account/password', [
        'current_password' => 'secret-123',
        'password' => 'new-secret-456',
        'password_confirmation' => 'new-secret-456',
    ])->assertOk();

    expect(Hash::check('new-secret-456', $admin->fresh()->password))->toBeTrue();
});

it('rejects password update when current password is invalid', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/admin/account/password', [
        'current_password' => 'invalid-current',
        'password' => 'new-secret-456',
        'password_confirmation' => 'new-secret-456',
    ])->assertUnprocessable()->assertJsonValidationErrors('current_password');
});

it('forbids non admin users from updating account routes', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('secret-123'),
    ]);

    Sanctum::actingAs($user);

    $this->putJson('/api/admin/account', [
        'name' => 'User',
        'email' => 'user@example.com',
    ])->assertForbidden();

    $this->putJson('/api/admin/account/password', [
        'current_password' => 'secret-123',
        'password' => 'new-secret-456',
        'password_confirmation' => 'new-secret-456',
    ])->assertForbidden();
});
