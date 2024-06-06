<?php

namespace App\Providers;

use App\Repositories\FileRepository;
use App\Repositories\FileRepositoryInterface;
use App\Services\FileService;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
