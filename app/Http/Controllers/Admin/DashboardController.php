<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'hotels' => Hotel::count(),
            'restaurants' => Restaurant::count(),
            'reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('canceled', 0)
                                                ->where('ended', 0)
                                                ->count(),
            'confirmed_reservations' => Reservation::where('canceled', 0)
                                                  ->where('ended', 0)
                                                  ->count(),
            'today_reservations' => Reservation::whereDate('day', today())->count(),
        ];

        $latest_reservations = Reservation::with(['restaurant'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latest_reservations'));
    }
}
