<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AISermonService;

class AIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AISermonService::class, function ($app) {
            return new AISermonService();
        });
    }

    public function boot()
    {
        //
    }
}