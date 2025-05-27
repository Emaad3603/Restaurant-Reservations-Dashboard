@extends('admin.layouts.app')

@section('title', 'Restaurant Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Restaurant Details</h1>
        <div>
            <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Restaurant
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Restaurants
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shop me-1"></i> Restaurant Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Restaurant ID</div>
                        <div class="col-md-9">{{ $restaurant->restaurants_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Name</div>
                        <div class="col-md-9">{{ $restaurant->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Hotel</div>
                        <div class="col-md-9">
                            @if($restaurant->hotel)
                                <a href="{{ route('admin.hotels.show', $restaurant->hotel) }}">
                                    {{ $restaurant->hotel->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Capacity</div>
                        <div class="col-md-9">{{ $restaurant->capacity ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Company ID</div>
                        <div class="col-md-9">{{ $restaurant->company_id ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status</div>
                        <div class="col-md-9">
                            @if($restaurant->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Always Paid/Free</div>
                        <div class="col-md-9">
                            @if($restaurant->always_paid_free)
                                <span class="badge bg-primary">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                    @if($restaurant->logo_url)
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Logo</div>
                            <div class="col-md-9">
                                <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }} Logo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created</div>
                        <div class="col-md-9">{{ $restaurant->created_at }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Last Updated</div>
                        <div class="col-md-9">{{ $restaurant->updated_at }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i> Reservations
                    </h5>
                </div>
                <div class="card-body">
                    @if($restaurant->reservations && $restaurant->reservations->count() > 0)
                        <ul class="list-group">
                            @foreach($restaurant->reservations as $reservation)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Reservation #{{ $reservation->reservations_id }}
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No reservations found for this restaurant.</p>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Reservation
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-1"></i> Pricing Times Management
                    </h5>
                </div>
                <div class="card-body">
                    <p>Manage this restaurant's pricing times and view their menus.</p>
                    <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-clock-history me-1"></i> View Pricing Times
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 