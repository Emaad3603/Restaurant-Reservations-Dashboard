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
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ReportController extends Controller
{
    /**
     * Display the reservations report page.
     */
    public function reservations(Request $request)
    {
        try {
            $companyId = \Illuminate\Support\Facades\Auth::user()->company_id;
            $hotels = Hotel::where('active', true)->get();
            $restaurants = Restaurant::where('active', true)->get();
            $mealTypes = MealType::where('active', true)->get();
            
            // Modified query to properly handle company_id
            $query = Reservation::query()
                ->with(['guestDetails', 'hotel', 'restaurant', 'mealType.translation'])
                ->where(function($q) use ($companyId) {
                    $q->where('reservations.company_id', $companyId)
                      ->orWhereHas('hotel', function($q) use ($companyId) {
                          $q->where('hotels.company_id', $companyId);
                      })
                      ->orWhereHas('restaurant', function($q) use ($companyId) {
                          $q->where('restaurants.company_id', $companyId);
                      });
                });
            
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

            // Get selected columns from request
            $selectedColumns = $request->input('columns', ['id', 'room', 'date', 'time', 'guest', 'hotel', 'restaurant', 'mealtype', 'guests', 'status']);

            $filters = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'hotel' => $request->hotel_id ? Hotel::find($request->hotel_id)->name : null,
                'restaurant' => $request->restaurant_id ? Restaurant::find($request->restaurant_id)->name : null,
                'meal_type' => $request->meal_type_id ? MealType::find($request->meal_type_id)->translation->name : null,
                'status' => $request->status
            ];

            // If print view is requested
            if ($request->has('print')) {
                return view('admin.reports.print-reservations', [
                    'reservations' => $reservations,
                    'filters' => $filters,
                    'title' => 'Reservations Report',
                    'selectedColumns' => $selectedColumns
                ]);
            }

            // If download is requested
            if ($request->has('download')) {
                $pdf = PDF::loadView('admin.reports.print-reservations', [
                    'reservations' => $reservations,
                    'filters' => $filters,
                    'title' => 'Reservations Report',
                    'selectedColumns' => $selectedColumns
                ]);

                // Set paper size to landscape
                $pdf->setPaper('a4', 'landscape');

                // Generate filename with date
                $filename = 'reservations-report-' . date('Y-m-d') . '.pdf';

                // Return the PDF for download
                return $pdf->download($filename);
            }
            
            return view('admin.reports.reservations', compact('reservations', 'hotels', 'restaurants', 'mealTypes'));
        } catch (Exception $e) {
            // Log the error
            Log::error('Error in reservations report: ' . $e->getMessage());
            
            // Return back with error message
            return back()->with('error', 'There was an error loading the reservations. Please try again.');
        }
    }
    
    /**
     * Generate summary statistics for the dashboard.
     */
    public function statistics(Request $request)
    {
        try {
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

            $data = [
                'totalReservations' => $totalReservations,
                'todayReservations' => $todayReservations,
                'weekReservations' => $weekReservations,
                'monthReservations' => $monthReservations,
                'reservationsByMealType' => $reservationsByMealType,
                'reservationsByRestaurant' => $reservationsByRestaurant,
                'reservationsByDay' => $reservationsByDay,
                'title' => 'Reservation Statistics'
            ];

            // If print view is requested
            if ($request->has('print')) {
                return view('admin.reports.print-statistics', $data);
            }

            // If download is requested
            if ($request->has('download')) {
                $pdf = PDF::loadView('admin.reports.print-statistics', $data);

                // Set paper size to landscape
                $pdf->setPaper('a4', 'landscape');

                // Generate filename with date
                $filename = 'reservation-statistics-' . date('Y-m-d') . '.pdf';

                // Return the PDF for download
                return $pdf->download($filename);
            }
            
            return view('admin.reports.statistics', $data);
        } catch (Exception $e) {
            // Log the error
            Log::error('Error in statistics report: ' . $e->getMessage());
            
            // Return back with error message
            return back()->with('error', 'There was an error loading the statistics. Please try again.');
        }
    }
} 