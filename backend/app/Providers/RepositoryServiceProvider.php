<?php

namespace App\Providers;

use App\Repositories\Task\ProviderAlphaRepository;
use App\Repositories\Task\ProviderAlphaRepositoryInterface;
use App\Repositories\Task\ProviderBetaRepository;
use App\Repositories\Task\ProviderBetaRepositoryInterface;
use App\Services\Task\TaskService;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(ProviderAlphaRepositoryInterface::class, ProviderAlphaRepository::class);
        $this->app->bind(ProviderBetaRepositoryInterface::class, ProviderBetaRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
