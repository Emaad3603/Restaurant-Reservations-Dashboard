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
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $menus = Menu::with(['categories.subcategories.items', 'categories.items'])
            ->where('company_id', $companyId)->get();

        return view('admin.menus.manage', compact('menus'));
    }

    public function manage()
    {
        $menus = Menu::with(['categories', 'categories.subcategories', 'categories.items'])
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
                'company_id' => Auth::user()->company_id
            ]);

            DB::commit();
            return response()->json(['success' => true, 'menu' => $menu]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Menu creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create menu'], 500);
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
                'active' => $request->active ?? $menu->active
            ]);

            DB::commit();
            return response()->json(['success' => true, 'menu' => $menu]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Menu update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update menu'], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            DB::beginTransaction();

            // Delete all related items first
            MenuItem::whereIn('category_id', $menu->categories->pluck('id'))->delete();
            
            // Delete all subcategories
            MenuSubcategory::whereIn('category_id', $menu->categories->pluck('id'))->delete();
            
            // Delete all categories
            $menu->categories()->delete();
            
            // Finally delete the menu
            $menu->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Menu deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete menu'], 500);
        }
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $category = MenuCategory::create([
                'menu_id' => $request->menu_id,
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? true
            ]);

            DB::commit();
            return response()->json(['success' => true, 'category' => $category]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create category'], 500);
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
                'active' => $request->active ?? $category->active
            ]);

            DB::commit();
            return response()->json(['success' => true, 'category' => $category]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update category'], 500);
        }
    }

    public function destroyCategory(MenuCategory $category)
    {
        try {
            DB::beginTransaction();

            // Delete all related items first
            $category->items()->delete();
            
            // Delete all subcategories
            $category->subcategories()->delete();
            
            // Finally delete the category
            $category->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete category'], 500);
        }
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $subcategory = MenuSubcategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? true
            ]);

            DB::commit();
            return response()->json(['success' => true, 'subcategory' => $subcategory]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subcategory creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create subcategory'], 500);
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
                'active' => $request->active ?? $subcategory->active
            ]);

            DB::commit();
            return response()->json(['success' => true, 'subcategory' => $subcategory]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subcategory update failed: ' . $e->getMessage());
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
            Log::error('Subcategory deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete subcategory'], 500);
        }
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'subcategory_id' => 'nullable|exists:menu_subcategories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $item = MenuItem::create([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'active' => $request->active ?? true
            ]);

            DB::commit();
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Item creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create item'], 500);
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
                'active' => $request->active ?? $item->active
            ]);

            DB::commit();
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Item update failed: ' . $e->getMessage());
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
            Log::error('Item deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete item'], 500);
        }
    }
}
