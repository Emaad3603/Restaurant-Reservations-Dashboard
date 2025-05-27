<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\MealType;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['restaurant', 'hotel', 'mealType', 'guestDetails'])
            ->latest()
            ->paginate(10);
            
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        $mealTypes = MealType::where('active', true)->get();
        $guests = Guest::all();
        
        return view('admin.reservations.create', compact('hotels', 'restaurants', 'mealTypes', 'guests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with(['restaurant', 'hotel', 'mealType', 'guestDetails'])
            ->findOrFail($id);
            
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reservation = Reservation::with(['restaurant', 'hotel', 'mealType', 'guest'])
            ->findOrFail($id);
            
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        $mealTypes = MealType::where('active', true)->get();
        $guests = Guest::all();
        
        return view('admin.reservations.edit', compact('reservation', 'hotels', 'restaurants', 'mealTypes', 'guests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function confirm($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $reservation->ended = 1;
        $reservation->save();
        return redirect()->back()->with('success', 'Reservation confirmed successfully.');
    }

    public function cancel($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $reservation->canceled = 1;
        $reservation->save();
        return redirect()->back()->with('success', 'Reservation cancelled successfully.');
    }
}
