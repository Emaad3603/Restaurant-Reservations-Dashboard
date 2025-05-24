<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MealType;
use App\Models\MealTypeTranslation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mealTypes = MealType::with(['translation', 'hotel', 'reservations'])
            ->withCount('reservations')
            ->latest()
            ->paginate(10);
            
        return view('admin.meal-types.index', compact('mealTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        
        return view('admin.meal-types.create', compact('hotels', 'restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'hotel_id' => 'nullable|exists:hotels,hotel_id',
            'restaurant_id' => 'nullable|exists:restaurants,restaurants_id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        
        try {
            $mealType = MealType::create([
                'hotel_id' => $request->hotel_id,
                'restaurant_id' => $request->restaurant_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'active' => $request->has('active'),
                'icon' => $request->icon,
            ]);
            
            // Create default translation for current locale
            MealTypeTranslation::create([
                'meal_type_id' => $mealType->meal_types_id,
                'name' => $request->name,
                'description' => $request->description,
                'locale' => app()->getLocale(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to create meal type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mealType = MealType::with(['translation', 'hotel', 'translations', 'reservations'])
            ->findOrFail($id);
            
        return view('admin.meal-types.show', compact('mealType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mealType = MealType::with('translation')->findOrFail($id);
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        
        return view('admin.meal-types.edit', compact('mealType', 'hotels', 'restaurants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'hotel_id' => 'nullable|exists:hotels,hotel_id',
            'restaurant_id' => 'nullable|exists:restaurants,restaurants_id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        
        try {
            $mealType = MealType::findOrFail($id);
            
            $mealType->update([
                'hotel_id' => $request->hotel_id,
                'restaurant_id' => $request->restaurant_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'active' => $request->has('active'),
                'icon' => $request->icon,
            ]);
            
            // Update or create translation
            MealTypeTranslation::updateOrCreate(
                [
                    'meal_type_id' => $mealType->meal_types_id,
                    'locale' => app()->getLocale()
                ],
                [
                    'name' => $request->name,
                    'description' => $request->description,
                ]
            );
            
            DB::commit();
            
            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to update meal type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mealType = MealType::findOrFail($id);
            
            // Check if there are any related reservations
            if ($mealType->reservations->count() > 0) {
                return back()->with('error', 'Cannot delete this meal type because it has associated reservations.');
            }
            
            $mealType->delete();
            
            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete meal type: ' . $e->getMessage());
        }
    }
}
