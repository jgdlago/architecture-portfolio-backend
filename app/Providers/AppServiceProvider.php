<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;
use App\RepositoryInterfaces\BaseRepositoryInterface;
use App\RepositoryInterfaces\ContactRepositoryInterface;
use App\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(ContactRepositoryInterface::class, ContactRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
