<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create or update the admin user from environment variables';

    public function handle(): int
    {
        $email = config('app.admin_email');
        $password = config('app.admin_password');
        $name = config('app.admin_name', 'Admin');

        if (! $email || ! $password) {
            $this->warn('ADMIN_EMAIL or ADMIN_PASSWORD not set — skipping admin creation.');

            return self::SUCCESS;
        }

        User::query()->updateOrCreate(
            ['email' => strtolower($email)],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ],
        );

        $this->info("Admin user [{$email}] ready.");

        return self::SUCCESS;
    }
}
