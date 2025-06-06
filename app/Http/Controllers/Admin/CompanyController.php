<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $companies = Company::with('currency')->paginate(10);
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
        $company->load('currency');
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $currencies = Currency::all();
        return view('admin.companies.edit', compact('company', 'currencies'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'currency_id' => 'required|exists:currencies,currencies_id',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'company_name' => $validated['company_name'],
            'currency_id' => $validated['currency_id']
        ];

        if ($request->hasFile('logo_url')) {
            // Delete old logo if exists
            if ($company->logo_url) {
                Storage::disk('public')->delete($company->logo_url);
            }
            
            $path = $request->file('logo_url')->store('company-logos', 'public');
            $data['logo_url'] = $path;
        }

        $company->update($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company updated successfully.');
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