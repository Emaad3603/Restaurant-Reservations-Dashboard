<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Restaurant::with(['hotel']);

        // Name search
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Hotel filter
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        // Location search
        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        $restaurants = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get hotels for filter
        $hotels = Hotel::where('active', true)->get();
        
        return view('admin.restaurants.index', compact('restaurants', 'hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::where('active', true)->get();
        return view('admin.restaurants.create', compact('hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'capacity' => 'nullable|integer',
            'logo_url' => 'nullable|image|max:2048',
            'active' => 'nullable|boolean',
            'always_paid_free' => 'required|in:null,1,0',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_url')) {
            $logoPath = $request->file('logo_url')->store('restaurants', 'public');
        }

        $hotel = Hotel::findOrFail($validated['hotel_id']);

        $restaurant = new Restaurant();
        $restaurant->name = $validated['name'];
        $restaurant->hotel_id = $validated['hotel_id'];
        $restaurant->capacity = $validated['capacity'] ?? null;
        $restaurant->company_id = $hotel->company_id;
        $restaurant->logo_url = $logoPath;
        $restaurant->active = $request->has('active');
        $restaurant->always_paid_free = $request->always_paid_free === 'null' ? null : (int)$request->always_paid_free;
        $restaurant->created_by = Auth::user()->user_name;
        $restaurant->created_at = now();
        $restaurant->save();

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $restaurant = Restaurant::with(['hotel', 'reservations'])
            ->findOrFail($id);
            
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $hotels = Hotel::where('active', true)->get();
        
        return view('admin.restaurants.edit', compact('restaurant', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'capacity' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'logo_url' => 'nullable|image|max:2048',
            'active' => 'nullable|boolean',
            'always_paid_free' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo_url')) {
            // Delete old logo if exists
            if ($restaurant->logo_url) {
                Storage::disk('public')->delete($restaurant->logo_url);
            }
            $restaurant->logo_url = $request->file('logo_url')->store('restaurants', 'public');
        }

        $restaurant->name = $validated['name'];
        $restaurant->hotel_id = $validated['hotel_id'];
        $restaurant->capacity = $validated['capacity'] ?? null;
        $restaurant->company_id = $validated['company_id'] ?? null;
        $restaurant->active = $request->has('active');
        $restaurant->always_paid_free = $request->has('always_paid_free');
        $restaurant->updated_by = Auth::user()->user_name;
        $restaurant->updated_at = now();
        $restaurant->save();

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $restaurant = Restaurant::findOrFail($id);
            
            // Check if there are any reservations
            if ($restaurant->reservations()->exists()) {
                return redirect()->route('admin.restaurants.index')
                    ->with('error', 'Cannot delete restaurant with existing reservations. Please ensure all reservations are handled first.');
            }

            // Delete restaurant translations first
            DB::table('restaurants_translations')
                ->where('restaurants_id', $restaurant->restaurants_id)
                ->delete();

            // Delete restaurant pricing times and related records
            $pricingTimes = DB::table('restaurant_pricing_times')
                ->where('restaurant_id', $restaurant->restaurants_id)
                ->get();

            foreach ($pricingTimes as $pricingTime) {
                // Delete related discounts
                DB::table('restaurant_pricing_times_discounts')
                    ->where('pricing_time_id', $pricingTime->id)
                    ->delete();
                
                // Delete related taxes
                DB::table('restaurant_pricing_times_taxes')
                    ->where('pricing_time_id', $pricingTime->id)
                    ->delete();
                
                // Delete the pricing time
                DB::table('restaurant_pricing_times')
                    ->where('id', $pricingTime->id)
                    ->delete();
            }

            // Delete menu items and their translations
            $menuItems = DB::table('items')
                ->where('company_id', $restaurant->company_id)
                ->get();

            foreach ($menuItems as $item) {
                // Delete item translations
                DB::table('items_translation')
                    ->where('items_id', $item->items_id)
                    ->delete();

                // Delete the menu item
                DB::table('items')
                    ->where('items_id', $item->items_id)
                    ->delete();
            }

            // Get menu categories
            $categories = DB::table('menu_categories')
                ->where('company_id', $restaurant->company_id)
                ->get();

            foreach ($categories as $category) {
                // Delete menu subcategories and their translations
                $subcategories = DB::table('menu_subcategories')
                    ->where('menu_categories_id', $category->menu_categories_id)
                    ->get();

                foreach ($subcategories as $subcategory) {
                    // Delete subcategory translations
                    DB::table('menu_subcategories_translation')
                        ->where('menu_subcategories_id', $subcategory->menu_subcategories_id)
                        ->delete();

                    // Delete the subcategory
                    DB::table('menu_subcategories')
                        ->where('menu_subcategories_id', $subcategory->menu_subcategories_id)
                        ->delete();
                }

                // Delete category translations
                DB::table('menu_categories_translation')
                    ->where('menu_categories_id', $category->menu_categories_id)
                    ->delete();

                // Delete the category
                DB::table('menu_categories')
                    ->where('menu_categories_id', $category->menu_categories_id)
                    ->delete();
            }

            // Finally delete the restaurant
            DB::table('restaurants')
                ->where('restaurants_id', $restaurant->restaurants_id)
                ->delete();
            
            DB::commit();
            
            return redirect()->route('admin.restaurants.index')
                ->with('success', 'Restaurant and all related data deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.restaurants.index')
                ->with('error', 'Failed to delete restaurant. Please try again later.');
        }
    }
}
