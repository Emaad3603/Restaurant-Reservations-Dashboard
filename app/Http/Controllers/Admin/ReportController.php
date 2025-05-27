<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MealType;
use App\Models\Reservation;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reservations report page.
     */
    public function reservations(Request $request)
    {
        $companyId = \Illuminate\Support\Facades\Auth::user()->company_id;
        $hotels = Hotel::where('active', true)->get();
        $restaurants = Restaurant::where('active', true)->get();
        $mealTypes = MealType::where('active', true)->get();
        $query = Reservation::query()
            ->with(['guestDetails', 'hotel', 'restaurant', 'mealType.translation'])
            ->where('reservations.company_id', $companyId);
        
        // Apply filters if provided
        if ($request->filled('start_date')) {
            $query->whereDate('day', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('day', '<=', $request->end_date);
        }
        
        if ($request->filled('hotel_id')) {
            $query->where('guest_hotel_id', $request->hotel_id);
        }
        
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        
        if ($request->filled('meal_type_id')) {
            $query->where('meal_types_id', $request->meal_type_id);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'canceled') {
                $query->where('canceled', 1);
            } elseif ($request->status === 'completed') {
                $query->where('ended', 1);
            } elseif ($request->status === 'pending') {
                $query->where('canceled', 0)->where('ended', 0);
            }
        }
        
        // Get the reservations
        $reservations = $query->latest()->paginate(20);
        
        return view('admin.reports.reservations', compact('reservations', 'hotels', 'restaurants', 'mealTypes'));
    }
    
    /**
     * Generate summary statistics for the dashboard.
     */
    public function statistics()
    {
        // Get today's date
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Count total reservations
        $totalReservations = Reservation::count();
        
        // Count today's reservations
        $todayReservations = Reservation::whereDate('day', $today)->count();
        
        // Count this week's reservations
        $weekReservations = Reservation::whereDate('day', '>=', $startOfWeek)
            ->whereDate('day', '<=', $endOfWeek)
            ->count();
        
        // Count this month's reservations
        $monthReservations = Reservation::whereDate('day', '>=', $startOfMonth)
            ->whereDate('day', '<=', $endOfMonth)
            ->count();
        
        // Get reservations by meal type (for pie chart)
        $reservationsByMealType = DB::table('reservations')
            ->join('meal_types', 'reservations.meal_types_id', '=', 'meal_types.meal_types_id')
            ->join('meal_types_translation', function($join) {
                $join->on('meal_types.meal_types_id', '=', 'meal_types_translation.meal_types_id')
                    ->where('meal_types_translation.language_code', '=', app()->getLocale());
            })
            ->select('meal_types_translation.name', DB::raw('count(*) as total'))
            ->groupBy('meal_types_translation.name')
            ->get();
        
        // Get reservations by restaurant (for bar chart)
        $reservationsByRestaurant = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.restaurants_id')
            ->select('restaurants.name', DB::raw('count(*) as total'))
            ->groupBy('restaurants.name')
            ->get();
        
        // Get reservations by day of week (for line chart)
        $reservationsByDay = DB::table('reservations')
            ->select(DB::raw('DAYNAME(day) as day'), DB::raw('count(*) as total'))
            ->groupBy('day')
            ->orderByRaw('FIELD(day, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();
        
        return view('admin.reports.statistics', compact(
            'totalReservations', 
            'todayReservations', 
            'weekReservations', 
            'monthReservations', 
            'reservationsByMealType', 
            'reservationsByRestaurant', 
            'reservationsByDay'
        ));
    }
} 