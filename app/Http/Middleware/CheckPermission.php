<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next, string $menuSlug, string $permission): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Get user's role - using direct User model
        /** @var User $user */
        $userRole = $user->userRoles()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            abort(403, 'Anda tidak memiliki role yang valid untuk mengakses sistem ini.');
        }

        // Check if user has permission for this menu and action
        $hasPermission = RolePermission::hasMenuPermission(
            $userRole->role->role_id, 
            $menuSlug, 
            $permission
        );

        if (!$hasPermission) {
            $roleName = $userRole->role->role_name;
            $actionName = $this->getActionName($permission);
            $menuName = $this->getMenuName($menuSlug);
            
            abort(403, "Role '{$roleName}' tidak memiliki izin untuk {$actionName} pada {$menuName}.");
        }

        return $next($request);
    }

    /**
     * Get user-friendly action name
     */
    private function getActionName(string $permission): string
    {
        $actions = [
            'create' => 'menambah data',
            'read' => 'melihat data', 
            'update' => 'mengubah data',
            'delete' => 'menghapus data'
        ];

        return $actions[$permission] ?? $permission;
    }

    /**
     * Get user-friendly menu name
     */
    private function getMenuName(string $menuSlug): string
    {
        $menus = [
            'dashboard' => 'Dashboard',
            'user' => 'Master User',
            'role' => 'Master Role',
            'menu' => 'Master Menu',
            'lesson_package' => 'Paket Les',
            'keuangan' => 'Keuangan',
            'keuangan-log' => 'Log Keuangan',
            'keuangan-tambah' => 'Tambah Log Keuangan',
            'laporan-keuangan' => 'Laporan Keuangan',
            'keuangan-dashboard' => 'Dashboard Keuangan',
            'settings' => 'Pengaturan',
            'attendance' => 'Attendance',
            'attendance_master' => 'Kelola Attendance',
            'attendance_guru' => 'Attendance Guru',
            'profile' => 'Profile',
            'history_transaksi' => 'History Transaksi'
        ];

        return $menus[$menuSlug] ?? ucwords(str_replace(['_', '-'], ' ', $menuSlug));
    }
}
