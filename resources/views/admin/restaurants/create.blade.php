@extends('admin.layouts.app')

@section('title', 'Add New Restaurant - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Restaurant</h1>
        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Restaurants
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Restaurant Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hotel_id" class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select class="form-select @error('hotel_id') is-invalid @enderror" id="hotel_id" name="hotel_id" required>
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->hotel_id }}" {{ old('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}">
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="always_paid_free" class="form-label">Payment Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('always_paid_free') is-invalid @enderror" id="always_paid_free" name="always_paid_free" required>
                                <option value="null" {{ old('always_paid_free') === 'null' ? 'selected' : '' }}>Free Count</option>
                                <option value="1" {{ old('always_paid_free') === '1' ? 'selected' : '' }}>Always Paid</option>
                                <option value="0" {{ old('always_paid_free') === '0' ? 'selected' : '' }}>Always Free</option>
                            </select>
                            @error('always_paid_free')
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
                    <button type="submit" class="btn btn-primary">Create Restaurant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 