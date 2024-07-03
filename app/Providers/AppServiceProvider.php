<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\ContactRepository;
use App\Repositories\ExperienceRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\RepositoryInterfaces\BaseRepositoryInterface;
use App\RepositoryInterfaces\ContactRepositoryInterface;
use App\RepositoryInterfaces\ExperienceRepositoryInterface;
use App\RepositoryInterfaces\ProjectRepositoryInterface;
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
        $this->app->singleton(ExperienceRepositoryInterface::class, ExperienceRepository::class);
        $this->app->singleton(ProjectRepositoryInterface::class, ProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
