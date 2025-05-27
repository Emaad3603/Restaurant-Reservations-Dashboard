@extends('admin.layouts.app')

@section('title', 'Reservations Report - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservations Report</h1>
        <div>
            <a href="{{ route('admin.reports.statistics') }}" class="btn btn-primary">
                <i class="bi bi-bar-chart me-1"></i> Statistics
            </a>
            <button onclick="window.print();" class="btn btn-info">
                <i class="bi bi-printer me-1"></i> Print Report
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Reservations</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.reservations') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="hotel_id" class="form-label">Hotel</label>
                        <select class="form-select" id="hotel_id" name="hotel_id">
                            <option value="">All Hotels</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->hotel_id }}" {{ request('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="restaurant_id" class="form-label">Restaurant</label>
                        <select class="form-select" id="restaurant_id" name="restaurant_id">
                            <option value="">All Restaurants</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->restaurants_id }}" {{ request('restaurant_id') == $restaurant->restaurants_id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="meal_type_id" class="form-label">Meal Type</label>
                        <select class="form-select" id="meal_type_id" name="meal_type_id">
                            <option value="">All Meal Types</option>
                            @foreach($mealTypes as $mealType)
                                <option value="{{ $mealType->meal_types_id }}" {{ request('meal_type_id') == $mealType->meal_types_id ? 'selected' : '' }}>
                                    {{ $mealType->translation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.reports.reservations') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room Number</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Guest</th>
                            <th>Hotel</th>
                            <th>Restaurant</th>
                            <th>Meal Type</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->reservations_id }}</td>
                                <td>{{ $reservation->room_number ?? 'N/A' }}</td>
                                <td>{{ $reservation->reservation_date ? date('Y-m-d', strtotime($reservation->reservation_date)) : ($reservation->day ?? 'N/A') }}</td>
                                <td>{{ $reservation->reservation_time ? date('h:i A', strtotime($reservation->reservation_time)) : ($reservation->time ? date('h:i A', strtotime($reservation->time)) : 'N/A') }}</td>
                                <td>{{ $reservation->guestNames ?? 'N/A' }}</td>
                                <td>{{ $reservation->hotel ? $reservation->hotel->name : 'N/A' }}</td>
                                <td>{{ $reservation->restaurant ? $reservation->restaurant->name : 'N/A' }}</td>
                                <td>{{ $reservation->mealType && $reservation->mealType->translation ? $reservation->mealType->translation->name : 'N/A' }}</td>
                                <td>{{ $reservation->people_count ?? $reservation->pax ?? 'N/A' }}</td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No reservations found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style media="print">
    .sidebar, .navbar, form, .card-header, .btn, .pagination, footer {
        display: none !important;
    }
    body {
        padding: 0;
        margin: 0;
    }
    .container-fluid {
        width: 100%;
        padding: 0;
    }
    table {
        width: 100%;
    }
</style>
@endpush

@endsection 