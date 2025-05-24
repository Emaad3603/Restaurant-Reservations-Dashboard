<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display the menu categories and items for a restaurant.
     */
    public function index(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $categories = MenuCategory::where('restaurant_id', $restaurantId)
            ->get();
            
        return view('admin.menu.index', compact('restaurant', 'categories'));
    }

    /**
     * Show form to create a new menu category.
     */
    public function createCategory($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        return view('admin.menu.create-category', compact('restaurant'));
    }

    /**
     * Store a new menu category.
     */
    public function storeCategory(Request $request, $restaurantId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $restaurant = Restaurant::findOrFail($restaurantId);
        
        try {
            DB::beginTransaction();
            
            $category = new MenuCategory();
            $category->label = $request->name;
            $category->background_url = $request->description ?? '';
            $category->restaurant_id = $restaurantId;
            $category->company_id = $restaurant->company_id;
            $category->created_by = 1;
            $category->updated_by = 1;
            $category->save();
            
            DB::commit();
            
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('success', 'Menu category created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Error creating menu category: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit a menu category.
     */
    public function editCategory($restaurantId, $categoryId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $category = MenuCategory::findOrFail($categoryId);
        
        // Ensure the category belongs to the restaurant
        if ($category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Category does not belong to this restaurant');
        }
        
        return view('admin.menu.edit-category', compact('restaurant', 'category'));
    }

    /**
     * Update a menu category.
     */
    public function updateCategory(Request $request, $restaurantId, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $restaurant = Restaurant::findOrFail($restaurantId);
        $category = MenuCategory::findOrFail($categoryId);
        
        // Ensure the category belongs to the restaurant
        if ($category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Category does not belong to this restaurant');
        }
        
        try {
            DB::beginTransaction();
            
            $category->label = $request->name;
            $category->background_url = $request->description ?? '';
            $category->updated_by = 1;
            $category->save();
            
            DB::commit();
            
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('success', 'Menu category updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Error updating menu category: ' . $e->getMessage());
        }
    }

    /**
     * Delete a menu category.
     */
    public function destroyCategory($restaurantId, $categoryId)
    {
        $category = MenuCategory::findOrFail($categoryId);
        
        // Ensure the category belongs to the restaurant
        if ($category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Category does not belong to this restaurant');
        }
        
        // Check if category has menu items
        if ($category->menuItems()->count() > 0) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Cannot delete category that has menu items');
        }
        
        try {
            DB::beginTransaction();
            
            $category->delete();
            
            DB::commit();
            
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('success', 'Menu category deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Error deleting menu category: ' . $e->getMessage());
        }
    }

    /**
     * Show form to create a new menu item.
     */
    public function createItem($restaurantId, $categoryId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $category = MenuCategory::findOrFail($categoryId);
        
        // Ensure the category belongs to the restaurant
        if ($category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Category does not belong to this restaurant');
        }
        
        return view('admin.menu.create-item', compact('restaurant', 'category'));
    }

    /**
     * Store a new menu item.
     */
    public function storeItem(Request $request, $restaurantId, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $category = MenuCategory::findOrFail($categoryId);
        
        // Ensure the category belongs to the restaurant
        if ($category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Category does not belong to this restaurant');
        }
        
        $item = new MenuItem();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->category_id = $categoryId;
        $item->active = $request->has('active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'menu_item_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('menu-items', $filename, 'public');
            $item->image = $path;
        }
        
        $item->save();

        return redirect()->route('admin.restaurants.menu.index', $restaurantId)
            ->with('success', 'Menu item created successfully');
    }

    /**
     * Show form to edit a menu item.
     */
    public function editItem($restaurantId, $itemId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $item = MenuItem::with('category')->findOrFail($itemId);
        
        // Ensure the item belongs to a category in this restaurant
        if ($item->category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Item does not belong to this restaurant');
        }
        
        return view('admin.menu.edit-item', compact('restaurant', 'item'));
    }

    /**
     * Update a menu item.
     */
    public function updateItem(Request $request, $restaurantId, $itemId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $item = MenuItem::with('category')->findOrFail($itemId);
        
        // Ensure the item belongs to a category in this restaurant
        if ($item->category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Item does not belong to this restaurant');
        }
        
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->active = $request->has('active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            
            $image = $request->file('image');
            $filename = 'menu_item_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('menu-items', $filename, 'public');
            $item->image = $path;
        }
        
        $item->save();

        return redirect()->route('admin.restaurants.menu.index', $restaurantId)
            ->with('success', 'Menu item updated successfully');
    }

    /**
     * Delete a menu item.
     */
    public function destroyItem($restaurantId, $itemId)
    {
        $item = MenuItem::with('category')->findOrFail($itemId);
        
        // Ensure the item belongs to a category in this restaurant
        if ($item->category->restaurant_id != $restaurantId) {
            return redirect()->route('admin.restaurants.menu.index', $restaurantId)
                ->with('error', 'Item does not belong to this restaurant');
        }
        
        // Delete the image if exists
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        
        $item->delete();
        
        return redirect()->route('admin.restaurants.menu.index', $restaurantId)
            ->with('success', 'Menu item deleted successfully');
    }
}
