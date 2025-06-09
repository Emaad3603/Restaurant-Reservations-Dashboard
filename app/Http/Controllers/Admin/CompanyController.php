<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Reservation;
use Exception;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $companies = Company::paginate(10);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('admin.companies.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'currency_id' => 'required|exists:currencies,currencies_id',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'company_name' => $validated['company_name'],
            'currency_id' => $validated['currency_id'],
            'company_uuid' => Str::uuid()->toString()
        ];

        if ($request->hasFile('logo_url')) {
            $path = $request->file('logo_url')->store('company-logos', 'public');
            $data['logo_url'] = $path;
        }

        Company::create($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        try {
            return view('admin.companies.show', compact('company'));
        } catch (Exception $e) {
            Log::error('Error in company show: ' . $e->getMessage());
            return back()->with('error', 'There was an error loading the company details. Please try again.');
        }
    }

    public function edit(Company $company)
    {
        $currencies = Currency::all();
        return view('admin.companies.edit', compact('company', 'currencies'));
    }

    public function update(Request $request, Company $company)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'currency_id' => 'required|exists:currencies,currencies_id',
                'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

            $data = [
                'company_name' => $validated['company_name'],
                'currency_id' => $validated['currency_id']
            ];

            // Handle file upload
            if ($request->hasFile('logo_url')) {
                try {
                    // Delete old logo if exists
                    if ($company->logo_url) {
                        $oldPath = str_replace(asset('storage/'), '', $company->logo_url);
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    
                    $file = $request->file('logo_url');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('company-logos', $filename, 'public');
                    
                    if ($path) {
                        $data['logo_url'] = $path;
                    }
                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error uploading file: ' . $e->getMessage());
                }
            }

            $company->update($data);

            return redirect()->route('admin.companies.index')
                ->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Company update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating company: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        if ($company->logo_url) {
            Storage::disk('public')->delete($company->logo_url);
        }
        
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
} 