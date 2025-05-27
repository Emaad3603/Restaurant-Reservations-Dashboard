<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\MealType;
use App\Models\RestaurantPricingTime;
use Illuminate\Http\Request;

class RestaurantPricingTimeController extends Controller
{
    public function create($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menus = Menu::where('company_id', $restaurant->company_id)->get();
        $mealTypes = MealType::where('company_id', $restaurant->company_id)->get();
        return view('admin.pricing_times.create', compact('restaurant', 'menus', 'mealTypes'));
    }

    public function store(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'price' => 'required',
            'menus_id' => 'required|exists:menus,menus_id',
            'meal_type' => 'required|string',
        ]);

        $date = \Carbon\Carbon::parse($request->date);
        $perPerson = $request->pricing_type === 'per_person' ? 1 : 0;
        $reservationByRoom = $request->pricing_type === 'per_room' ? 1 : 0;

        $pricingTime = new RestaurantPricingTime();
        $pricingTime->restaurant_id = $restaurantId;
        $pricingTime->company_id = $restaurant->company_id;
        $pricingTime->year = $date->format('Y');
        $pricingTime->month = $date->format('m');
        $pricingTime->day = $date->format('d');
        $pricingTime->time = $request->time;
        $pricingTime->price = $request->price;
        $pricingTime->menus_id = $request->menus_id;
        $pricingTime->meal_type = $request->meal_type;
        $pricingTime->per_person = $perPerson;
        $pricingTime->reservation_by_room = $reservationByRoom;
        $pricingTime->extra_seats = $request->extra_seats ?? 0;
        $pricingTime->menu_url = $request->menu_url ?? null;
        $pricingTime->calculate_price = $request->calculate_price ?? 1;
        $pricingTime->save();

        return redirect()->route('admin.restaurants.menu.index', $restaurantId)
            ->with('success', 'Pricing time added successfully!');
    }

    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $pricingTimes = RestaurantPricingTime::where('restaurant_id', $restaurantId)->get();
        return view('admin.pricing_times.index', compact('restaurant', 'pricingTimes'));
    }

    public function show($restaurantId, RestaurantPricingTime $pricingTime)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        return view('admin.pricing_times.show', compact('restaurant', 'pricingTime'));
    }

    public function edit($restaurantId, RestaurantPricingTime $pricingTime)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menus = Menu::where('company_id', $restaurant->company_id)->get();
        $mealTypes = MealType::where('company_id', $restaurant->company_id)->get();
        return view('admin.pricing_times.edit', compact('restaurant', 'pricingTime', 'menus', 'mealTypes'));
    }

    public function update(Request $request, $restaurantId, RestaurantPricingTime $pricingTime)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'price' => 'required',
            'menus_id' => 'required|exists:menus,menus_id',
            'meal_type' => 'required|string',
        ]);
        $date = \Carbon\Carbon::parse($request->date);
        $perPerson = $request->pricing_type === 'per_person' ? 1 : 0;
        $reservationByRoom = $request->pricing_type === 'per_room' ? 1 : 0;
        $pricingTime->year = $date->format('Y');
        $pricingTime->month = $date->format('m');
        $pricingTime->day = $date->format('d');
        $pricingTime->time = $request->time;
        $pricingTime->price = $request->price;
        $pricingTime->menus_id = $request->menus_id;
        $pricingTime->meal_type = $request->meal_type;
        $pricingTime->per_person = $perPerson;
        $pricingTime->reservation_by_room = $reservationByRoom;
        $pricingTime->extra_seats = $request->extra_seats ?? 0;
        $pricingTime->menu_url = $request->menu_url ?? null;
        $pricingTime->calculate_price = $request->calculate_price ?? 1;
        $pricingTime->save();
        return redirect()->route('admin.restaurants.pricing-times.index', $restaurantId)
            ->with('success', 'Pricing time updated successfully!');
    }

    public function destroy($restaurantId, RestaurantPricingTime $pricingTime)
    {
        $pricingTime->delete();
        return redirect()->route('admin.restaurants.pricing-times.index', $restaurantId)
            ->with('success', 'Pricing time deleted successfully!');
    }

    public function menu($restaurantId, RestaurantPricingTime $pricingTime)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menu = \App\Models\Menu::find($pricingTime->menus_id);
        return view('admin.pricing_times.menu', compact('restaurant', 'pricingTime', 'menu'));
    }
} 