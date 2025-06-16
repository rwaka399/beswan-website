<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share master menus with all master layout views
        View::composer('master.*', function ($view) {
            $user = auth()->user();
            $userMenuIds = [];
            
            if ($user) {
                // Get user's role menu IDs through role-menu relationship
                $userMenuIds = DB::table('user_roles')
                    ->join('role_menus', 'user_roles.role_id', '=', 'role_menus.role_id')
                    ->where('user_roles.user_id', $user->user_id)
                    ->pluck('role_menus.menu_id')
                    ->toArray();
            }
              // Get menus that user has access to
            $menuQuery = Menu::whereIn('menu_slug', [
                'dashboard', 'user', 'role', 'menu', 'lesson_package', 'keuangan', 'settings', 'attendance', 'home', 'logout'
            ])
            ->whereNull('menu_parent')
            ->orderBy('menu_urutan');
            
            // Filter by user's accessible menus
            if (!empty($userMenuIds)) {
                $menuQuery->whereIn('menu_id', $userMenuIds);
            } else {
                // If user has no role-menu access, return empty collection
                $menuQuery->where('menu_id', -1); // This will return no results
            }
            
            $menus = $menuQuery->with(['children' => function($query) use ($userMenuIds) {
                // Filter children menu based on user's role-menu access
                if (!empty($userMenuIds)) {
                    $query->whereIn('menu_id', $userMenuIds);
                } else {
                    $query->where('menu_id', -1); // No access
                }
                $query->orderBy('menu_urutan');
            }])
            ->get();

            $view->with('menus', $menus);
        });

        // Share profile menus with all profile layout views
        View::composer('profile.*', function ($view) {
            $menus = Menu::whereIn('menu_slug', [
                'profile', 'history_transaksi', 'home', 'logout'
            ])
            ->whereNull('menu_parent')
            ->orderBy('menu_urutan')
            ->get();

            $view->with('menus', $menus);
        });
    }
}
