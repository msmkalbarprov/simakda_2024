<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('akses', function ($user) {
            $route = Route::currentRouteName();
            $id = Auth::user()->id;

            $hak_akses = DB::table('user_role as a')->select('c.name')->join('permission_role as b', 'a.id_role', '=', 'b.id_role')->join('permission as c', 'b.id_permission', '=', 'c.id')->where(['a.id_role' => $id])->get();
            $hak = [];
            foreach ($hak_akses as $akses) {
                $hak[] = $akses->name;
            }
            if (!in_array($route, $hak)) {
                return false;
            } else {
                return true;
            }
        });
    }
}
