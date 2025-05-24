@extends('admin.layouts.app')

@section('title', 'Hotel Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hotel Details</h1>
        <div>
            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Hotel
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Hotels
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-1"></i> Hotel Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Hotel ID</div>
                        <div class="col-md-9">{{ $hotel->hotel_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Name</div>
                        <div class="col-md-9">{{ $hotel->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status</div>
                        <div class="col-md-9">
                            @if($hotel->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Verification Type</div>
                        <div class="col-md-9">{{ $hotel->verification_type ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Company ID</div>
                        <div class="col-md-9">{{ $hotel->company_id ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Time Zone</div>
                        <div class="col-md-9">{{ $hotel->time_zone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Days Adjustment</div>
                        <div class="col-md-9">
                            <span class="d-block">Plus: {{ $hotel->plus_days_adjust ?? '0' }} days</span>
                            <span class="d-block">Minus: {{ $hotel->minus_days_adjust ?? '0' }} days</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Free Count</div>
                        <div class="col-md-9">{{ $hotel->free_count ?? '0' }}</div>
                    </div>
                    @if($hotel->logo_url)
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Logo</div>
                            <div class="col-md-9">
                                <img src="{{ $hotel->logo_url }}" alt="{{ $hotel->name }} Logo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created</div>
                        <div class="col-md-9">{{ $hotel->created_at }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Last Updated</div>
                        <div class="col-md-9">{{ $hotel->updated_at }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shop me-1"></i> Restaurants
                    </h5>
                </div>
                <div class="card-body">
                    @if($hotel->restaurants->count() > 0)
                        <ul class="list-group">
                            @foreach($hotel->restaurants as $restaurant)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $restaurant->name }}
                                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No restaurants found for this hotel.</p>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Restaurant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection