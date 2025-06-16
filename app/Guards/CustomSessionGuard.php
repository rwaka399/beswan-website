<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\RolePermission;

class CustomSessionGuard implements Guard
{
    protected $name;
    protected $user;
    protected $provider;
    protected $session;
    protected $lastAttempted;
    protected $cookieJar;
    protected $dispatcher;
    protected $request;

    public function __construct($name, UserProvider $provider, Session $session)
    {
        $this->name = $name;
        $this->provider = $provider;
        $this->session = $session;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->get($this->getName());

        if (!is_null($id)) {
            $this->user = $this->provider->retrieveById($id);
        }

        return $this->user;
    }

    public function id()
    {
        if ($user = $this->user()) {
            return $user->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

        return $user && $this->provider->validateCredentials($user, $credentials);
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);
            return true;
        }

        return false;
    }

    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    public function login($user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        // Claim all user data including role, menus, and permissions
        $this->claimUserData($user);

        $this->setUser($user);
    }

    protected function claimUserData($user)
    {
        // Get user role
        $userRole = $user->userRoles()->with('role')->first();
        
        if ($userRole && $userRole->role) {
            $role = $userRole->role;
            
            // Get user menus based on role
            $roleMenus = RoleMenu::where('role_id', $role->role_id)
                ->with(['menu' => function($query) {
                    $query->orderBy('menu_urutan');
                }])
                ->get();

            // Get user permissions
            $rolePermissions = RolePermission::where('role_id', $role->role_id)
                ->where('value', true)
                ->with('menu')
                ->get();

            // Build menu tree
            $menuTree = $this->buildMenuTree($roleMenus);

            // Build permissions array
            $permissions = $this->buildPermissionsArray($rolePermissions);

            // Store in session
            $this->session->put('user_data', [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => [
                    'role_id' => $role->role_id,
                    'role_name' => $role->role_name,
                    'role_description' => $role->role_description
                ],
                'menus' => $menuTree,
                'permissions' => $permissions,
                'menu_slugs' => $roleMenus->pluck('menu.menu_slug')->filter()->toArray()
            ]);
        }
    }

    protected function buildMenuTree($roleMenus)
    {
        $menus = [];
        $parentMenus = [];
        $childMenus = [];

        // Separate parent and child menus
        foreach ($roleMenus as $roleMenu) {
            if ($roleMenu->menu) {
                if ($roleMenu->menu->menu_parent) {
                    $childMenus[] = $roleMenu->menu;
                } else {
                    $parentMenus[] = $roleMenu->menu;
                }
            }
        }

        // Build tree
        foreach ($parentMenus as $parent) {
            $parentData = [
                'menu_id' => $parent->menu_id,
                'menu_name' => $parent->menu_name,
                'menu_slug' => $parent->menu_slug,
                'menu_icon' => $parent->menu_icon,
                'menu_link' => $parent->menu_link,
                'menu_type' => $parent->menu_type,
                'menu_urutan' => $parent->menu_urutan,
                'children' => []
            ];

            // Add children
            foreach ($childMenus as $child) {
                if ($child->menu_parent == $parent->menu_id) {
                    $parentData['children'][] = [
                        'menu_id' => $child->menu_id,
                        'menu_name' => $child->menu_name,
                        'menu_slug' => $child->menu_slug,
                        'menu_icon' => $child->menu_icon,
                        'menu_link' => $child->menu_link,
                        'menu_type' => $child->menu_type,
                        'menu_urutan' => $child->menu_urutan
                    ];
                }
            }

            // Sort children by menu_urutan
            usort($parentData['children'], function($a, $b) {
                return $a['menu_urutan'] <=> $b['menu_urutan'];
            });

            $menus[] = $parentData;
        }

        // Sort parent menus by menu_urutan
        usort($menus, function($a, $b) {
            return $a['menu_urutan'] <=> $b['menu_urutan'];
        });

        return $menus;
    }

    protected function buildPermissionsArray($rolePermissions)
    {
        $permissions = [];
        
        foreach ($rolePermissions as $permission) {
            if ($permission->menu) {
                $menuSlug = $permission->menu->menu_slug;
                if (!isset($permissions[$menuSlug])) {
                    $permissions[$menuSlug] = [];
                }
                $permissions[$menuSlug][] = $permission->slug;
            }
        }

        return $permissions;
    }

    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);
        $this->session->migrate(true);
    }

    public function logout()
    {
        $this->session->remove($this->getName());
        $this->session->remove('user_data');
        $this->user = null;
    }

    public function getName()
    {
        return 'login_' . $this->name . '_' . sha1(static::class);
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }

    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    // Helper methods to get claimed data
    public function getUserData()
    {
        return $this->session->get('user_data', []);
    }

    public function getUserRole()
    {
        $userData = $this->getUserData();
        return $userData['role'] ?? null;
    }

    public function getUserMenus()
    {
        $userData = $this->getUserData();
        return $userData['menus'] ?? [];
    }

    public function getUserPermissions()
    {
        $userData = $this->getUserData();
        return $userData['permissions'] ?? [];
    }

    public function hasPermission($menuSlug, $permission)
    {
        $permissions = $this->getUserPermissions();
        return isset($permissions[$menuSlug]) && in_array($permission, $permissions[$menuSlug]);
    }

    public function hasUser()
    {
        return !is_null($this->user);
    }
}
