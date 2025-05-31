@extends('admin.layouts.app')

@section('title', 'Edit Reservation - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Reservation</h1>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Reservations
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="reservation_date" class="form-label">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" id="reservation_date" name="reservation_date" value="{{ old('reservation_date', $reservation->reservation_date ? date('Y-m-d', strtotime($reservation->reservation_date)) : '') }}" required>
                            @error('reservation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="reservation_time" class="form-label">Reservation Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('reservation_time') is-invalid @enderror" id="reservation_time" name="reservation_time" value="{{ old('reservation_time', $reservation->reservation_time ? date('H:i', strtotime($reservation->reservation_time)) : '') }}" required>
                            @error('reservation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="guest_id" class="form-label">Guest <span class="text-danger">*</span></label>
                            <select class="form-select @error('guest_id') is-invalid @enderror" id="guest_id" name="guest_reservations_id" required>
                                <option value="">Select Guest</option>
                                @foreach($guests as $guest)
                                    <option value="{{ $guest->guest_details_id }}" {{ old('guest_reservations_id', $reservation->guest_reservations_id) == $guest->guest_details_id ? 'selected' : '' }}>
                                        {{ $guest->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guest_reservations_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="people_count" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('people_count') is-invalid @enderror" id="people_count" name="people_count" value="{{ old('people_count', $reservation->people_count) }}" min="1" required>
                            @error('people_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hotel_id" class="form-label">Hotel</label>
                            <select class="form-select @error('hotel_id') is-invalid @enderror" id="hotel_id" name="guest_hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->hotel_id }}" {{ old('guest_hotel_id', $reservation->guest_hotel_id) == $hotel->hotel_id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guest_hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="restaurant_id" class="form-label">Restaurant <span class="text-danger">*</span></label>
                            <select class="form-select @error('restaurant_id') is-invalid @enderror" id="restaurant_id" name="restaurant_id" required>
                                <option value="">Select Restaurant</option>
                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->restaurants_id }}" {{ old('restaurant_id', $reservation->restaurant_id) == $restaurant->restaurants_id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('restaurant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meal_type_id" class="form-label">Meal Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('meal_type_id') is-invalid @enderror" id="meal_type_id" name="meal_types_id" required>
                                <option value="">Select Meal Type</option>
                                @foreach($mealTypes as $mealType)
                                    <option value="{{ $mealType->meal_types_id }}" {{ old('meal_types_id', $reservation->meal_types_id) == $mealType->meal_types_id ? 'selected' : '' }}>
                                        {{ $mealType->translation->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meal_types_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="board_type" class="form-label">Board Type</label>
                            <select class="form-select @error('board_type') is-invalid @enderror" id="board_type" name="board_type">
                                <option value="">Select Board Type</option>
                                @foreach($boardTypes as $boardType)
                                    <option value="{{ $boardType->board_id }}" {{ old('board_type', $reservation->guestReservation->board_type) == $boardType->board_id ? 'selected' : '' }}>
                                        {{ $boardType->board_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('board_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $reservation->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="no_show" {{ $reservation->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="special_request" class="form-label">Special Requests</label>
                    <textarea class="form-control @error('special_request') is-invalid @enderror" id="special_request" name="special_request" rows="3">{{ old('special_request', $reservation->special_request) }}</textarea>
                    @error('special_request')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 