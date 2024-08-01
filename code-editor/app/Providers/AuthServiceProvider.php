<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\AdminPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => AdminPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-admin', [AdminPolicy::class, 'accessAdmin']);
    }
}
