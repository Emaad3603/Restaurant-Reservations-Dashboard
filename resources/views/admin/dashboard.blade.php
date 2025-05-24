@extends('admin.layouts.app')

@section('title', 'Dashboard - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="bi bi-building fs-1 mb-2"></i>
                <h2>{{ $stats['hotels'] }}</h2>
                <p>Hotels</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="bi bi-shop fs-1 mb-2"></i>
                <h2>{{ $stats['restaurants'] }}</h2>
                <p>Restaurants</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="bi bi-calendar-check fs-1 mb-2"></i>
                <h2>{{ $stats['reservations'] }}</h2>
                <p>Total Reservations</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="bi bi-calendar-date fs-1 mb-2"></i>
                <h2>{{ $stats['today_reservations'] }}</h2>
                <p>Today's Reservations</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Latest Reservations</h6>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Restaurant</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latest_reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->guest->name ?? 'N/A' }}</td>
                                    <td>{{ $reservation->restaurant->name ?? 'N/A' }}</td>
                                    <td>{{ $reservation->reservation_date ? date('M d, Y', strtotime($reservation->reservation_date)) : 'N/A' }}</td>
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No reservations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Reservation Status</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Pending</h5>
                                    <p class="card-text display-4">{{ $stats['pending_reservations'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Confirmed</h5>
                                    <p class="card-text display-4">{{ $stats['confirmed_reservations'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 