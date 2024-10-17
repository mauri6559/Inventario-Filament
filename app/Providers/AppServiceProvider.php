<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        FacadesGate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['es','en','fr']); // also accepts a closure
        });
    }
}
