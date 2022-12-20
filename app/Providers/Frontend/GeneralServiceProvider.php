<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\GeneralRepository;
use App\Repository\Frontend\GeneralRepositoryImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class GeneralServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        GeneralRepository::class => GeneralRepositoryImplement::class
    ];

    public function provides(): array
    {
        return [GeneralRepository::class];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
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
