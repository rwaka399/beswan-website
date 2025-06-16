<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\RolePermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::with('creator'); // Eager load relasi creator
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(role_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(role_description) LIKE ?', ["%{$search}%"]);
            });
        }
        $roles = $query->paginate(10)->appends(['search' => $request->search]);
        return view('master.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all menus for permission assignment
        $menus = Menu::whereNull('menu_parent')
            ->with(['children' => function($query) {
                $query->orderBy('menu_urutan');
            }])
            ->orderBy('menu_urutan')
            ->get();
        
        // Available permission types
        $availablePermissions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'manage' => 'Manage',
            'config' => 'Config',
            'execute' => 'Execute'
        ];

        return view('master.role.create', compact('menus', 'availablePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles',
            'role_description' => 'required|string|max:255',
            'menus' => 'array',
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            // Create role
            $role = Role::create([
                'role_name' => $request->role_name,
                'role_description' => $request->role_description,
                'created_by' => Auth::user()->user_id,
            ]);

            // Create role menus
            if ($request->filled('menus')) {
                foreach ($request->menus as $menuId) {
                    RoleMenu::create([
                        'role_id' => $role->role_id,
                        'menu_id' => $menuId,
                        'created_by' => Auth::user()->user_id,
                        'updated_by' => Auth::user()->user_id,
                    ]);
                }
            }

            // Create role permissions
            if ($request->filled('permissions')) {
                foreach ($request->permissions as $menuId => $permissions) {
                    // Get role_menu_id for this menu
                    $roleMenu = RoleMenu::where('role_id', $role->role_id)->where('menu_id', $menuId)->first();
                    
                    if ($roleMenu) {
                        foreach ($permissions as $permission) {
                            RolePermission::create([
                                'role_id' => $role->role_id,
                                'menu_id' => $menuId,
                                'role_menu_id' => $roleMenu->role_menu_id,
                                'slug' => $permission,
                                'value' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('role-index')->with('success', 'Role and permissions created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create role. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        // Get all menus
        $menus = Menu::whereNull('menu_parent')
            ->with(['children' => function($query) {
                $query->orderBy('menu_urutan');
            }])
            ->orderBy('menu_urutan')
            ->get();
        
        // Get current role menus
        $roleMenus = RoleMenu::where('role_id', $role->role_id)
            ->pluck('menu_id')
            ->toArray();
        
        // Get current permissions grouped by menu
        $rolePermissions = RolePermission::where('role_id', $role->role_id)
            ->get()
            ->groupBy('menu_id');
        
        // Available permission types
        $availablePermissions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'manage' => 'Manage',
            'config' => 'Config',
            'execute' => 'Execute'
        ];

        return view('master.role.edit', compact('role', 'menus', 'roleMenus', 'rolePermissions', 'availablePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $id . ',role_id',
            'role_description' => 'nullable|string|max:255',
            'menus' => 'array',
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            // Update role data
            Role::where('role_id', $id)->update([
                'role_name'         => $request->role_name,
                'role_description'  => $request->role_description,
                'updated_at'        => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_by'        => Auth::user()->user_id,
            ]);

            // Update role menus
            RoleMenu::where('role_id', $id)->delete();
            
            if ($request->filled('menus')) {
                foreach ($request->menus as $menuId) {
                    RoleMenu::create([
                        'role_id' => $id,
                        'menu_id' => $menuId,
                        'created_by' => Auth::user()->user_id,
                        'updated_by' => Auth::user()->user_id,
                    ]);
                }
            }

            // Update role permissions
            RolePermission::where('role_id', $id)->delete();
            
            if ($request->filled('permissions')) {
                foreach ($request->permissions as $menuId => $permissions) {
                    // Get role_menu_id for this menu
                    $roleMenu = RoleMenu::where('role_id', $id)->where('menu_id', $menuId)->first();
                    
                    if ($roleMenu) {
                        foreach ($permissions as $permission) {
                            RolePermission::create([
                                'role_id' => $id,
                                'menu_id' => $menuId,
                                'role_menu_id' => $roleMenu->role_menu_id,
                                'slug' => $permission,
                                'value' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('role-index')->with('success', 'Role and permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update role. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            DB::commit();
            return redirect()->route('role-index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete role.');
        }
    }
}
