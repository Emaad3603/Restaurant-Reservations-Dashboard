<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\MealType;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\BoardType;
use App\Models\GuestReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['restaurant', 'hotel', 'mealType', 'guestDetails']);

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('day', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('day', '<=', $request->end_date);
        }

        // Hotel filter
        if ($request->filled('hotel_id')) {
            $query->where('guest_hotel_id', $request->hotel_id);
        }

        // Restaurant filter
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Meal type filter
        if ($request->filled('meal_type_id')) {
            $query->where('meal_types_id', $request->meal_type_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Guest filter
        if ($request->filled('guest_id')) {
            $query->where('guest_reservations_id', $request->guest_id);
        }

        // Board type filter
        if ($request->filled('board_type')) {
            $query->whereHas('guestReservation', function($q) use ($request) {
                $q->where('board_type', $request->board_type);
            });
        }

        // Search by room number
        if ($request->filled('room_number')) {
            $query->where('room_number', 'like', '%' . $request->room_number . '%');
        }

        $reservations = $query->latest()->paginate(10)->withQueryString();

        // Get filter options
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        $mealTypes = MealType::where('active', true)->get();
        $guests = Guest::all();
        $boardTypes = BoardType::all();
        
        return view('admin.reservations.index', compact(
            'reservations',
            'hotels',
            'restaurants',
            'mealTypes',
            'guests',
            'boardTypes'
        ));
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
        $boardTypes = BoardType::all();
        
        return view('admin.reservations.create', compact('hotels', 'restaurants', 'mealTypes', 'guests', 'boardTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_reservations_id' => 'required|exists:guest_details,guest_details_id',
            'people_count' => 'required|integer|min:1',
            'guest_hotel_id' => 'nullable|exists:hotels,hotel_id',
            'restaurant_id' => 'required|exists:restaurants,restaurants_id',
            'meal_types_id' => 'required|exists:meal_types,meal_types_id',
            'board_type' => 'nullable|exists:board_type_rules,board_id',
            'special_request' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Create guest reservation record
            $guestReservation = GuestReservation::create([
                'room_number' => $request->room_number,
                'arrival_date' => $request->reservation_date,
                'departure_date' => $request->reservation_date,
                'pax' => $request->people_count,
                'status' => 'pending',
                'hotel_id' => $request->guest_hotel_id,
                'company_id' => Auth::user()->company_id,
                'board_type' => $request->board_type
            ]);

            // Create reservation record
            $reservation = Reservation::create([
                'guest_reservations_id' => $guestReservation->guest_reservations_id,
                'room_number' => $request->room_number,
                'pax' => $request->people_count,
                'restaurant_id' => $request->restaurant_id,
                'day' => $request->reservation_date,
                'time' => $request->reservation_time,
                'company_id' => Auth::user()->company_id,
                'guest_hotel_id' => $request->guest_hotel_id,
                'meal_types_id' => $request->meal_types_id,
                'created_by' => Auth::user()->user_name,
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to create reservation: ' . $e->getMessage())
                ->withInput();
        }
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
        $reservation = Reservation::with(['restaurant', 'hotel', 'mealType', 'guestDetails'])
            ->findOrFail($id);
            
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        $mealTypes = MealType::where('active', true)->get();
        $guests = Guest::all();
        $boardTypes = BoardType::all();
        
        return view('admin.reservations.edit', compact('reservation', 'hotels', 'restaurants', 'mealTypes', 'guests', 'boardTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_reservations_id' => 'required|exists:guest_details,guest_details_id',
            'people_count' => 'required|integer|min:1',
            'guest_hotel_id' => 'nullable|exists:hotels,hotel_id',
            'restaurant_id' => 'required|exists:restaurants,restaurants_id',
            'meal_types_id' => 'required|exists:meal_types,meal_types_id',
            'board_type' => 'nullable|exists:board_type_rules,board_id',
            'special_request' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);
            $guestReservation = $reservation->guestReservation;

            // Update guest reservation
            $guestReservation->update([
                'room_number' => $request->room_number,
                'arrival_date' => $request->reservation_date,
                'departure_date' => $request->reservation_date,
                'pax' => $request->people_count,
                'hotel_id' => $request->guest_hotel_id,
                'board_type' => $request->board_type
            ]);

            // Update reservation
            $reservation->update([
                'room_number' => $request->room_number,
                'pax' => $request->people_count,
                'restaurant_id' => $request->restaurant_id,
                'day' => $request->reservation_date,
                'time' => $request->reservation_time,
                'guest_hotel_id' => $request->guest_hotel_id,
                'meal_types_id' => $request->meal_types_id,
                'updated_by' => Auth::user()->user_name,
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to update reservation: ' . $e->getMessage())
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
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            DB::commit();
            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Failed to delete reservation: ' . $e->getMessage());
        }
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
