<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::replacer('unique', function ($message, $attribute, $rule, $parameters) {

            if ($attribute === 'email') {
                return "Cet email existe déjà.";
            }

            return $message;
        });
    }
}
