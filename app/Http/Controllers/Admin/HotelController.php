<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Hotel::query();

        // Name search
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Location search
        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('country', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        $hotels = $query->withCount('restaurants')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Get companies for filter
        $companies = \App\Models\Company::all();
        
        return view('admin.hotels.index', compact('hotels', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'logo_url' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $logoPath = $file->storeAs('hotels', $filename, 'public');
        }

        $hotel = new Hotel($validated);
        $hotel->logo_url = $logoPath;
        $hotel->created_by = Auth::user()->user_name;
        $hotel->created_at = now();
        $hotel->save();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('restaurants');
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        $companies = \App\Models\Company::all();
        return view('admin.hotels.edit', compact('hotel', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'verification_type' => 'nullable|integer',
                'company_id' => 'required|integer',
                'free_count' => 'nullable|integer',
                'time_zone' => 'nullable|string|max:45',
                'plus_days_adjust' => 'nullable|integer',
                'minus_days_adjust' => 'nullable|integer',
                'logo_url' => 'nullable|image|max:2048',
                'active' => 'boolean',
                'restricted_restaurants' => 'boolean',
            ]);

            // Handle file upload
            if ($request->hasFile('logo_url')) {
                try {
                    // Delete old logo if exists
                    if ($hotel->logo_url) {
                        $oldPath = str_replace(asset('storage/'), '', $hotel->logo_url);
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    
                    $file = $request->file('logo_url');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('hotels', $filename, 'public');
                    
                    if ($path) {
                        $hotel->logo_url = $path;
                    }
                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error uploading file: ' . $e->getMessage());
                }
            }

            // Update other fields
            $hotel->name = $validated['name'];
            $hotel->verification_type = $validated['verification_type'] ?? 0;
            $hotel->company_id = $validated['company_id'];
            $hotel->free_count = $validated['free_count'] ?? 0;
            $hotel->time_zone = $validated['time_zone'] ?? '+02:00';
            $hotel->plus_days_adjust = $validated['plus_days_adjust'] ?? 0;
            $hotel->minus_days_adjust = $validated['minus_days_adjust'] ?? 0;
            $hotel->active = $request->has('active');
            $hotel->restricted_restaurants = $request->has('restricted_restaurants');
            
            $hotel->updated_by = Auth::user()->user_name;
            $hotel->updated_at = now();
            $hotel->save();

            return redirect()->route('admin.hotels.index')
                ->with('success', 'Hotel updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Hotel update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating hotel: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }
}
