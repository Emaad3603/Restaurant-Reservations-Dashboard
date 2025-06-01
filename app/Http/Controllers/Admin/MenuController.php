<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $menus = Menu::with(['categories.subcategories', 'categories'])
            ->where('company_id', $companyId)->get();
        $currencies = DB::table('currencies')->where('company_id', $companyId)->get();
        return view('admin.menus.manage', compact('menus', 'currencies'));
    }

    public function manage()
    {
        $menus = Menu::with(['categories', 'categories.subcategories'])
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('admin.menus.manage', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $menu = Menu::create([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? true,
                'company_id' => Auth::user()->company_id,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('admin.menus.manage')->with('success', 'Menu created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create menu: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $menu->update([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? $menu->active,
                'updated_by' => Auth::user()->user_name,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'menu' => $menu]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update menu'], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            DB::beginTransaction();

            // Delete all related items first
            MenuItem::where('menus_id', $menu->menus_id)->delete();
            
            // Delete all subcategories
            MenuSubcategory::whereIn('menu_categories_id', $menu->categories->pluck('menu_categories_id'))->delete();
            
            // Delete all categories
            $menu->categories()->delete();
            
            // Finally delete the menu
            $menu->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete menu'], 500);
        }
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'menus_id' => 'required|exists:menus,menus_id',
            'label' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $category = MenuCategory::create([
                'menus_id' => $request->menus_id,
                'label' => $request->label,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('admin.menus.manage')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function updateCategory(Request $request, MenuCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? $category->active,
                'updated_by' => Auth::user()->user_name,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'category' => $category]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update category'], 500);
        }
    }

    public function destroyCategory(MenuCategory $category)
    {
        try {
            DB::beginTransaction();

            // Delete all subcategories
            MenuSubcategory::where('menu_categories_id', $category->menu_categories_id)->delete();
            
            // Finally delete the category
            $category->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete category'], 500);
        }
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'menu_categories_id' => 'required|exists:menu_categories,menu_categories_id',
            'label' => 'required|string|max:255',
            'background_url' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subcategory = MenuSubcategory::create([
                'menu_categories_id' => $request->menu_categories_id,
                'label' => $request->label,
                'background_url' => $request->background_url,
                'company_id' => Auth::user()->company_id,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('admin.menus.manage')->with('success', 'Subcategory created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create subcategory: ' . $e->getMessage());
        }
    }

    public function updateSubcategory(Request $request, MenuSubcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $subcategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? $subcategory->active,
                'updated_by' => Auth::user()->user_name,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'subcategory' => $subcategory]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update subcategory'], 500);
        }
    }

    public function destroySubcategory(MenuSubcategory $subcategory)
    {
        try {
            DB::beginTransaction();

            // Delete all related items first
            $subcategory->items()->delete();
            
            // Finally delete the subcategory
            $subcategory->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete subcategory'], 500);
        }
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'menus_id' => 'required|exists:menus,menus_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currencies_id' => 'required|exists:currencies,currencies_id',
        ]);

        try {
            DB::beginTransaction();

            // Create a new Item record
            $item = \App\Models\Item::create([
                'label' => $request->name,
                'company_id' => Auth::user()->company_id,
                'menu_categories_id' => $request->menu_categories_id,
                'menu_subcategories_id' => $request->menu_subcategories_id ?? null,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            // Create the MenuItem record using the new items_id
            $menuItem = MenuItem::create([
                'menus_id' => $request->menus_id,
                'items_id' => $item->items_id,
                'price' => $request->price,
                'currencies_id' => $request->currencies_id,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            DB::commit();
            return redirect()->route('admin.menus.manage')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $item->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'active' => $request->active ?? $item->active,
                'updated_by' => Auth::user()->user_name,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update item'], 500);
        }
    }

    public function destroyItem(MenuItem $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete item'], 500);
        }
    }
}
