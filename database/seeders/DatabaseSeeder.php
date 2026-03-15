<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->where('is_admin', true)->first();

        // ENV admin is only the initial bootstrap account for production.
        if (! $admin) {
            $admin = User::query()->firstOrCreate(
                ['email' => (string) config('app.admin_email', 'admin@example.com')],
                [
                    'name' => (string) config('app.admin_name', 'Admin'),
                    'password' => Hash::make((string) config('app.admin_password', 'password')),
                ],
            );
        }

        $admin->forceFill(['is_admin' => true])->save();

        $this->syncAllAdminPermissions($admin);

        $this->call([
            SiteSettingsSeeder::class,
            PortfolioCatalogSeeder::class,
        ]);
    }

    private function syncAllAdminPermissions(User $admin): void
    {
        // Keep compatibility if permission package is not installed in this environment.
        if (! class_exists(Permission::class) || ! method_exists($admin, 'syncPermissions')) {
            return;
        }

        $permissionNames = Permission::query()->pluck('name')->all();

        $admin->syncPermissions($permissionNames);
    }
}
