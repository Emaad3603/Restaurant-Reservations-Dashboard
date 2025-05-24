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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date & Time</th>
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
                                <td>
                                    {{ $reservation->reservation_date ? date('Y-m-d', strtotime($reservation->reservation_date)) : 'N/A' }}
                                    <br>
                                    {{ $reservation->reservation_time ? date('h:i A', strtotime($reservation->reservation_time)) : 'N/A' }}
                                </td>
                                <td>
                                    @if($reservation->guest)
                                        {{ $reservation->guest->name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->hotel)
                                        {{ $reservation->hotel->name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->restaurant)
                                        {{ $reservation->restaurant->name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->mealType)
                                        {{ $reservation->mealType->translation->name ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $reservation->people_count ?? 'N/A' }}</td>
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No reservations found</td>
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