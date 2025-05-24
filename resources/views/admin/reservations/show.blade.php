@extends('admin.layouts.app')

@section('title', 'Reservation Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservation Details</h1>
        <div>
            <a href="{{ route('admin.reservations.edit', $reservation) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Reservation
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Reservations
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i> Reservation Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Reservation ID</div>
                        <div class="col-md-9">{{ $reservation->reservations_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Date</div>
                        <div class="col-md-9">{{ $reservation->reservation_date ? date('Y-m-d', strtotime($reservation->reservation_date)) : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Time</div>
                        <div class="col-md-9">{{ $reservation->reservation_time ? date('h:i A', strtotime($reservation->reservation_time)) : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status</div>
                        <div class="col-md-9">
                            @if($reservation->status == \App\Models\Reservation::STATUS_PENDING)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($reservation->status == \App\Models\Reservation::STATUS_CONFIRMED)
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($reservation->status == \App\Models\Reservation::STATUS_CANCELLED)
                                <span class="badge bg-danger">Cancelled</span>
                            @elseif($reservation->status == \App\Models\Reservation::STATUS_COMPLETED)
                                <span class="badge bg-info">Completed</span>
                            @elseif($reservation->status == \App\Models\Reservation::STATUS_NO_SHOW)
                                <span class="badge bg-secondary">No Show</span>
                            @else
                                <span class="badge bg-secondary">Unknown</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Guest</div>
                        <div class="col-md-9">
                            @if($reservation->guest)
                                {{ $reservation->guest->name }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Hotel</div>
                        <div class="col-md-9">
                            @if($reservation->hotel)
                                <a href="{{ route('admin.hotels.show', $reservation->hotel) }}">
                                    {{ $reservation->hotel->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Restaurant</div>
                        <div class="col-md-9">
                            @if($reservation->restaurant)
                                <a href="{{ route('admin.restaurants.show', $reservation->restaurant) }}">
                                    {{ $reservation->restaurant->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Meal Type</div>
                        <div class="col-md-9">
                            @if($reservation->mealType)
                                {{ $reservation->mealType->translation->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Number of Guests</div>
                        <div class="col-md-9">{{ $reservation->people_count ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Special Requests</div>
                        <div class="col-md-9">{{ $reservation->special_request ?? 'None' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-1"></i> Reservation History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title">Created</h3>
                                <p>{{ $reservation->created_at ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        @if($reservation->ended == 1)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title">Completed</h3>
                                <p>{{ $reservation->updated_at ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        @elseif($reservation->canceled == 1)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h3 class="timeline-title">Cancelled</h3>
                                <p>{{ $reservation->updated_at ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 