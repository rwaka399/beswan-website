<?php

namespace App\Http\Controllers\Master;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    

    public function index(Request $request)
    {
        $query = Menu::with(['creator', 'updater', 'parent', 'children'])
            ->orderBy('menu_parent')
            ->orderBy('menu_urutan')
            ->get();

        return view('master.menu.index', compact('query'));
    }

    public function create()
    {
        $parentMenus = Menu::whereNull('menu_parent')->orderBy('menu_urutan')->get();
        return view('master.menu.create', compact('parentMenus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:100',
            'menu_type' => 'required|string|max:100',
            'menu_icon' => 'nullable|string|max:100',
            'menu_link' => 'nullable|string|max:100',
            'menu_urutan' => 'required|integer',
            'menu_parent' => 'nullable|exists:menus,menu_id',
            'menu_slug' => 'required|string|max:100|unique:menus',
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::create([
                'menu_name' => $request->menu_name,
                'menu_type' => $request->menu_type,
                'menu_icon' => $request->menu_icon,
                'menu_link' => $request->menu_link,
                'menu_urutan' => $request->menu_urutan,
                'menu_parent' => $request->menu_parent,
                'menu_slug' => Str::slug($request->menu_slug),
                'created_by' => Auth::user()->user_id,
            ]);

            // Auto-assign menu baru ke role Admin (role_id = 1)
            $adminRole = \App\Models\Role::where('role_name', 'Admin')->first();
            if ($adminRole) {
                \App\Models\RoleMenu::create([
                    'role_id' => $adminRole->role_id,
                    'menu_id' => $menu->menu_id,
                    'created_by' => Auth::user()->user_id,
                    'updated_by' => Auth::user()->user_id,
                ]);
            }

            DB::commit();
            
            // Refresh menu session untuk semua user yang login
            $this->refreshMenuSession();
            
            return redirect()->route('menu-index')->with('success', 'Menu created successfully and assigned to Admin role.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create menu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $menu = Menu::with('creator', 'updater')->findOrFail($id);
        return view('master.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $parentMenus = Menu::whereNull('menu_parent')
            ->where('menu_id', '!=', $id)
            ->orderBy('menu_urutan')
            ->get();

        return view('master.menu.edit', compact('menu', 'parentMenus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_name' => 'required|string|max:100',
            'menu_type' => 'required|string|max:100',
            'menu_icon' => 'nullable|string|max:100',
            'menu_link' => 'nullable|string|max:100',
            'menu_urutan' => 'required|integer',
            'menu_parent' => 'nullable|exists:menus,menu_id',
            'menu_slug' => 'required|string|max:100|unique:menus,menu_slug,' . $id . ',menu_id',
        ]);

        DB::beginTransaction();
        try {
            Menu::where('menu_id', $id)->update([
                'menu_name' => $request->menu_name,
                'menu_type' => $request->menu_type,
                'menu_icon' => $request->menu_icon,
                'menu_link' => $request->menu_link,
                'menu_urutan' => $request->menu_urutan,
                'menu_parent' => $request->menu_parent,
                'menu_slug' => Str::slug($request->menu_slug),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->user_id,
            ]);

            DB::commit();
            
            // Refresh menu session untuk semua user yang login
            $this->refreshMenuSession();
            
            return redirect()->route('menu-index')->with('success', 'Menu updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update menu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);

            // Check if menu has children
            $hasChildren = Menu::where('menu_parent', $id)->exists();
            if ($hasChildren) {
                return redirect()->back()->with('error', 'Cannot delete menu that has sub-menus. Please delete sub-menus first.');
            }

            $menu->delete();

            DB::commit();
            
            // Refresh menu session untuk semua user yang login
            $this->refreshMenuSession();
            
            return redirect()->route('menu-index')->with('success', 'Menu deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete menu: ' . $e->getMessage());
        }
    }

    /**
     * Get menu tree structure for API or AJAX requests
     */
    public function getMenuTree()
    {
        $menus = Menu::whereNull('menu_parent')
            ->with(['children' => function ($query) {
                $query->orderBy('menu_urutan');
            }])
            ->orderBy('menu_urutan')
            ->get();

        return response()->json($menus);
    }

    /**
     * Get sub-menus by parent ID
     */
    public function getSubMenus($parentId)
    {
        $subMenus = Menu::where('menu_parent', $parentId)
            ->orderBy('menu_urutan')
            ->get();

        return response()->json($subMenus);
    }

    /**
     * Get menu statistics for display
     */
    private function getMenuStats($menus)
    {
        $stats = [
            'total' => 0,
            'parents' => 0,
            'children' => 0
        ];

        if (is_countable($menus)) {
            $stats['total'] = count($menus);
            foreach ($menus as $menu) {
                if ($menu->menu_parent) {
                    $stats['children']++;
                } else {
                    $stats['parents']++;
                }
            }
        } elseif (method_exists($menus, 'total')) {
            $stats['total'] = $menus->total();
            // For paginated data, we need to check each item in current page
            foreach ($menus as $menu) {
                if ($menu->menu_parent) {
                    $stats['children']++;
                } else {
                    $stats['parents']++;
                }
            }
        }

        return $stats;
    }

    /**
     * Refresh menu session untuk user yang sedang login
     */
    private function refreshMenuSession()
    {
        $guard = Auth::guard('web');
        if ($guard instanceof \App\Guards\CustomSessionGuard) {
            $guard->refreshUserMenus();
        }
    }
}
