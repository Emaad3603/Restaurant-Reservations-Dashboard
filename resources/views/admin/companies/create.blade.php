@extends('admin.layouts.app')

@section('title', 'Create Company - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Company</h1>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Companies
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="currency_id" class="form-label">Currency</label>
                            <select class="form-select @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->currencies_id }}" {{ old('currency_id') == $currency->currencies_id ? 'selected' : '' }}>
                                        {{ $currency->name }} ({{ $currency->currency_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo_url" class="form-label">Company Logo</label>
                            <input type="file" class="form-control @error('logo_url') is-invalid @enderror" id="logo_url" name="logo_url" accept="image/*">
                            @error('logo_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Create Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 