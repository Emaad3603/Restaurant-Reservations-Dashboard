<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardType;
use App\Models\Hotel;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoardTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BoardType::with(['hotel', 'company']);

        // Board name search
        if ($request->filled('board_name')) {
            $query->where('board_name', 'like', '%' . $request->board_name . '%');
        }

        // Board ID search
        if ($request->filled('board_id')) {
            $query->where('board_id', 'like', '%' . $request->board_id . '%');
        }

        // Hotel filter
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Free count filter
        if ($request->filled('free_count')) {
            $query->where('free_count', $request->free_count);
        }

        $boardTypes = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get filter options
        $hotels = Hotel::where('active', true)->get();
        $companies = Company::all();
        
        return view('admin.board-types.index', compact('boardTypes', 'hotels', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::where('active', true)->get();
        $companies = Company::all();
        
        return view('admin.board-types.create', compact('hotels', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'board_name' => 'required|string|max:255',
            'board_id' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,company_id',
            'hotel_id' => 'nullable|exists:hotels,hotel_id',
            'free_count' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            BoardType::create([
                'board_name' => $request->board_name,
                'board_id' => $request->board_id,
                'company_id' => $request->company_id,
                'hotel_id' => $request->hotel_id,
                'free_count' => $request->free_count,
            ]);

            DB::commit();

            return redirect()->route('admin.board-types.index')
                ->with('success', 'Board type created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create board type: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to create board type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BoardType $boardType)
    {
        $boardType->load(['hotel', 'company']);
        return view('admin.board-types.show', compact('boardType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoardType $boardType)
    {
        $hotels = Hotel::where('active', true)->get();
        $companies = Company::all();
        
        return view('admin.board-types.edit', compact('boardType', 'hotels', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoardType $boardType)
    {
        $request->validate([
            'board_name' => 'required|string|max:255',
            'board_id' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,company_id',
            'hotel_id' => 'nullable|exists:hotels,hotel_id',
            'free_count' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $boardType->update([
                'board_name' => $request->board_name,
                'board_id' => $request->board_id,
                'company_id' => $request->company_id,
                'hotel_id' => $request->hotel_id,
                'free_count' => $request->free_count,
            ]);

            DB::commit();

            return redirect()->route('admin.board-types.index')
                ->with('success', 'Board type updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update board type: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to update board type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardType $boardType)
    {
        try {
            $boardType->delete();
            
            return redirect()->route('admin.board-types.index')
                ->with('success', 'Board type deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete board type: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete board type: ' . $e->getMessage());
        }
    }
} 