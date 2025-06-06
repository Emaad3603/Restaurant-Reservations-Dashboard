@extends('admin.layouts.app')

@section('title', 'Edit Hotel - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Hotel</h1>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Hotels
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $hotel->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->company_id }}" {{ old('company_id', $hotel->company_id) == $company->company_id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="verification_type" class="form-label">Verification Type</label>
                            <select class="form-select @error('verification_type') is-invalid @enderror" id="verification_type" name="verification_type">
                                <option value="0" {{ old('verification_type', $hotel->verification_type) == 0 ? 'selected' : '' }}>None</option>
                                <option value="1" {{ old('verification_type', $hotel->verification_type) == 1 ? 'selected' : '' }}>Email</option>
                                <option value="2" {{ old('verification_type', $hotel->verification_type) == 2 ? 'selected' : '' }}>Phone</option>
                            </select>
                            @error('verification_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="time_zone" class="form-label">Time Zone</label>
                            <input type="text" class="form-control @error('time_zone') is-invalid @enderror" id="time_zone" name="time_zone" value="{{ old('time_zone', $hotel->time_zone) }}" placeholder="+02:00">
                            @error('time_zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="free_count" class="form-label">Free Count</label>
                            <input type="number" class="form-control @error('free_count') is-invalid @enderror" id="free_count" name="free_count" value="{{ old('free_count', $hotel->free_count) }}" min="0">
                            @error('free_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="plus_days_adjust" class="form-label">Plus Days Adjust</label>
                            <input type="number" class="form-control @error('plus_days_adjust') is-invalid @enderror" id="plus_days_adjust" name="plus_days_adjust" value="{{ old('plus_days_adjust', $hotel->plus_days_adjust) }}" min="0">
                            @error('plus_days_adjust')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="minus_days_adjust" class="form-label">Minus Days Adjust</label>
                            <input type="number" class="form-control @error('minus_days_adjust') is-invalid @enderror" id="minus_days_adjust" name="minus_days_adjust" value="{{ old('minus_days_adjust', $hotel->minus_days_adjust) }}" min="0">
                            @error('minus_days_adjust')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="logo_url" class="form-label">Logo</label>
                    <input type="file" class="form-control @error('logo_url') is-invalid @enderror" id="logo_url" name="logo_url" accept="image/*">
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($hotel->logo_url)
                        <div class="mt-2">
                            <img src="{{ $hotel->logo_url }}" alt="Logo Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $hotel->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="restricted_restaurants" name="restricted_restaurants" value="1" {{ old('restricted_restaurants', $hotel->restricted_restaurants) ? 'checked' : '' }}>
                            <label class="form-check-label" for="restricted_restaurants">Restricted Restaurants</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 