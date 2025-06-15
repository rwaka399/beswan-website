<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuController extends Controller
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
        $query = Menu::with('creator');
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(menu_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(menu_type) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(menu_link) LIKE ?', ["%{$search}%"]);
            });
        }

        $menus = $query->orderBy('menu_urutan')->paginate(10)->appends(['search' => $request->search]);
        return view('master.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
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
            Menu::create([
                'menu_name' => $request->menu_name,
                'menu_type' => $request->menu_type,
                'menu_icon' => $request->menu_icon,
                'menu_link' => $request->menu_link,
                'menu_urutan' => $request->menu_urutan,
                'menu_parent' => $request->menu_parent,
                'menu_slug' => Str::slug($request->menu_slug),
                'created_by' => Auth::user()->user_id,
            ]);

            DB::commit();
            return redirect()->route('menu-index')->with('success', 'Menu created successfully.');
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
}
