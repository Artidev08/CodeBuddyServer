<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
{
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
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework.
     *
     * @return void
    */
    public function boot()
    {
        // Registers a view composer that applies to all views.
        View::composer('*', function ($view) {
            // Attempt to get the currently authenticated user.
            $auth_user_master = Auth::user(); // Get the authenticated user's ID
            if($auth_user_master){
                // Define a cache key unique to the authenticated user.
                $cacheKey = 'user_permissions_roles_' . $auth_user_master->id;

                // Attempt to retrieve data from cache or compute it if not available.
                $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($auth_user_master) {
                    $auth_user_master = Auth::user() ? User::with('roles.permissions')->find(auth()->id()) : null;
                    $master_permissions = $auth_user_master && $auth_user_master->roles->isNotEmpty() ? $auth_user_master->roles[0]['permissions']->pluck('name') : collect();
                    $master_setting = Setting::select('id', 'key', 'value')->get();
                    return compact('master_permissions', 'auth_user_master', 'master_setting');
                });
                // Attach the data to all views, making it globally accessible.
                $view->with($data);
            }
        });
    }
}
