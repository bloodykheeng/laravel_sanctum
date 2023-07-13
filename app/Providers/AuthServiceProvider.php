<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // i added this myself to add expiration of the logged in user to one day
        PersonalAccessToken::deleting(function (PersonalAccessToken $token) {
            // Add any additional logic you need before deleting the token

            // Delete the token
            $token->forceDelete();
        });
    }
}
