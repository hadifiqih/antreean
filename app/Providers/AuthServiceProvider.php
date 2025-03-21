<?php

namespace App\Providers;

use App\Models\Antrian;
use App\Models\Offer;
use App\Policies\AntrianPolicy;
use App\Policies\OfferPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Offer::class => OfferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('delete', AntrianPolicy::class.'@delete'); 
    }
}
