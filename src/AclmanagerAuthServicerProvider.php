<?php

namespace Yashiroiori\AclManagerTabler;

use App\Models\Role;
use App\Models\User;
use Yashiroiori\AclManagerTabler\Policies\RolePolicy;
use Yashiroiori\AclManagerTabler\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AclmanagerAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
