<?php

namespace App\Helpers;

class IconHelper
{
    /**
     * Convert FontAwesome icon to SVG icon
     */
    public static function getFontAwesomeToSvg($iconClass)
    {
        $iconMap = [
            'fas fa-tachometer-alt' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" />',
            'fas fa-users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
            'fas fa-user-tag' => '<path d="M12 2a5 5 0 0 0-5 5v3a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2V7a5 5 0 0 0-5-5z" /><path d="M9 15l2 2 4-4" />',
            'fas fa-bars' => '<rect width="3" height="3" x="9" y="9" rx="0.5"/><rect width="3" height="3" x="9" y="15" rx="0.5"/><rect width="3" height="3" x="15" y="9" rx="0.5"/><rect width="3" height="3" x="15" y="15" rx="0.5"/><rect width="3" height="3" x="3" y="9" rx="0.5"/><rect width="3" height="3" x="3" y="15" rx="0.5"/>',
            'fas fa-chalkboard-teacher' => '<line x1="3" y1="6" x2="15" y2="6" /><line x1="3" y1="12" x2="15" y2="12" /><line x1="3" y1="18" x2="15" y2="18" /><path d="M19 6l1.5 1.5L19 9l1.5 1.5L19 12l1.5 1.5L19 15l1.5 1.5L19 18" />',
            'fas fa-money-bill-wave' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
            'fas fa-cog' => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>',
            'fas fa-check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
            'fas fa-home' => '<path d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5.25a.75.75 0 01-.75-.75v-5.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75v5.5a.75.75 0 01-.75.75H4a1 1 0 01-1-1V9.75z" />',
            'fas fa-user' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
            'fas fa-history' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M9 21a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />',
            'fas fa-sign-out-alt' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" y1="12" x2="9" y2="12" />',
            'fas fa-clipboard-check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M15 13l-3 3m0 0l-3-3m3 3V8"/>',
            'fas fa-user-check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>',
            // Additional common icons
            'fas fa-list' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
            'fas fa-layer-group' => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
            'fas fa-sitemap' => '<path d="M8 6h8v2H8V6zm0 4h8v2H8v-2zm0 4h8v2H8v-2z"/><path d="M4 2h16v20H4V2z"/>',
            'fas fa-plus' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
            'fas fa-edit' => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
            'fas fa-trash' => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c0-1 1-2 2-2v2"/>',
            'fas fa-eye' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'fas fa-calendar' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'fas fa-book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
            'fas fa-graduation-cap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
        ];

        return $iconMap[$iconClass] ?? '<circle cx="12" cy="12" r="3" />';
    }

    /**
     * Get route name from menu link
     */
    public static function getRouteFromLink($menuLink)
    {
        if (!$menuLink) {
            return null;
        }

        // Normalize the link (remove leading slash)
        $normalizedLink = ltrim($menuLink, '/');

        $routeMap = [
            '' => 'home',
            '/' => 'home',
            'master' => 'dashboard',
            'master/user' => 'user-index',
            'master/role' => 'role-index',
            'master/menu' => 'menu-index',
            'master/menu/create' => 'menu-create',
            'master/lesson_package' => 'lesson-package-index',
            'master/financial' => 'financial-index',
            'master/financial/create' => 'financial-create',
            'master/financial/report' => 'financial-report',
            'master/financial/dashboard' => 'financial-dashboard',
            'master/attendance' => 'master.attendance.index',
            'master/attendance/create' => 'master.attendance.create',
            'guru/attendance' => 'teacher.attendance.index',
            'guru/attendance/history' => 'teacher.attendance.history',
            'profile' => 'profile-index',
            'profile/history' => 'history',
            'logout' => 'logout',
        ];

        // Check both original and normalized links
        if (isset($routeMap[$menuLink])) {
            return $routeMap[$menuLink];
        }
        
        if (isset($routeMap[$normalizedLink])) {
            return $routeMap[$normalizedLink];
        }

        // For new menus, try to auto-generate route name
        if (strpos($normalizedLink, 'master/') === 0) {
            $slug = str_replace('master/', '', $normalizedLink);
            $routeName = str_replace(['/', '-'], ['-', '-'], $slug) . '-index';
            
            // Check if route exists
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                return $routeName;
            }
        }

        // Check if it's a valid URL (external)
        if (filter_var($menuLink, FILTER_VALIDATE_URL)) {
            return $menuLink;
        }

        // If no route found, return null (will show "Coming Soon")
        return null;
    }
}
