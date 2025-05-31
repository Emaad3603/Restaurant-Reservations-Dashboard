<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MealType;
use App\Models\MealTypeTranslation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MealTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MealType::with(['translation', 'hotel', 'reservations'])
            ->withCount('reservations');

        // Name search
        if ($request->filled('name')) {
            $query->whereHas('translation', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Hotel filter
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $mealTypes = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get filter options
        $hotels = Hotel::where('active', true)->get();
        $companies = \App\Models\Company::all();
        
        return view('admin.meal-types.index', compact('mealTypes', 'hotels', 'companies'));
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
            'label' => 'required|string|max:255',
            'company_id' => 'nullable|integer',
            'active' => 'sometimes|boolean',
        ]);

        try {
            $mealType = MealType::create([
                'label' => $request->label,
                'company_id' => $request->company_id,
                'active' => $request->has('active'),
            ]);

            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type created successfully.');
        } catch (\Exception $e) {
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
            'label' => 'required|string|max:255',
            'company_id' => 'nullable|integer',
            'active' => 'sometimes|boolean',
        ]);

        try {
            $mealType = MealType::findOrFail($id);

            $mealType->update([
                'label' => $request->label,
                'company_id' => $request->company_id,
                'active' => $request->has('active'),
            ]);

            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type updated successfully.');
        } catch (\Exception $e) {
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
            DB::beginTransaction();
            
            // Delete meal type translations first
            DB::table('meal_types_translation')
                ->where('meal_types_id', $id)
                ->delete();

            // Delete the meal type
            DB::table('meal_types')
                ->where('meal_types_id', $id)
                ->delete();
            
            DB::commit();
            
            return redirect()->route('admin.meal-types.index')
                ->with('success', 'Meal type deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Meal type deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.meal-types.index')
                ->with('error', 'Failed to delete meal type. Please try again later.');
        }
    }
}
