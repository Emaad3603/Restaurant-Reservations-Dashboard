@extends('admin.layouts.app')

@section('title', 'Reservations - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservations</h1>
        <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Reservation
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="guest_id" class="form-label">Guest</label>
                    <select class="form-select" id="guest_id" name="guest_id">
                        <option value="">All Guests</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->guest_details_id }}" {{ request('guest_id') == $guest->guest_details_id ? 'selected' : '' }}>
                                {{ $guest->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="board_type" class="form-label">Board Type</label>
                    <select class="form-select" id="board_type" name="board_type">
                        <option value="">All Board Types</option>
                        @foreach($boardTypes as $boardType)
                            <option value="{{ $boardType->board_id }}" {{ request('board_type') == $boardType->board_id ? 'selected' : '' }}>
                                {{ $boardType->board_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="text" class="form-control" id="room_number" name="room_number" value="{{ request('room_number') }}" placeholder="Search by room number">
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
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
                                        <a href="{{ route('admin.reservations.edit', $reservation) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                        </form>
                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">Cancel</button>
                                        </form>
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
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 