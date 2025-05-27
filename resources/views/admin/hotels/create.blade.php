@extends('admin.layouts.app')

@section('title', 'Add New Hotel - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Hotel</h1>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Hotels
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="time_zone" class="form-label">Time Zone</label>
                            <input type="text" class="form-control @error('time_zone') is-invalid @enderror" id="time_zone" name="time_zone" value="{{ old('time_zone') }}">
                            @error('time_zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="verification_type" class="form-label">Verification Type</label>
                            <input type="text" class="form-control @error('verification_type') is-invalid @enderror" id="verification_type" name="verification_type" value="{{ old('verification_type') }}">
                            @error('verification_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company ID</label>
                            <input type="number" class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" value="{{ old('company_id') }}">
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="free_count" class="form-label">Free Count</label>
                            <input type="number" class="form-control @error('free_count') is-invalid @enderror" id="free_count" name="free_count" value="{{ old('free_count', 0) }}">
                            @error('free_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="plus_days_adjust" class="form-label">Plus Days Adjust</label>
                            <input type="number" class="form-control @error('plus_days_adjust') is-invalid @enderror" id="plus_days_adjust" name="plus_days_adjust" value="{{ old('plus_days_adjust', 0) }}">
                            @error('plus_days_adjust')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="minus_days_adjust" class="form-label">Minus Days Adjust</label>
                            <input type="number" class="form-control @error('minus_days_adjust') is-invalid @enderror" id="minus_days_adjust" name="minus_days_adjust" value="{{ old('minus_days_adjust', 0) }}">
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
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 