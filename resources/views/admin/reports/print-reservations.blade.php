@extends('admin.reports.print-layout')

@section('content')
<div class="metrics">
    <div class="metric-card">
        <h3>Total Reservations</h3>
        <div class="value">{{ $reservations->total() }}</div>
    </div>
    <div class="metric-card">
        <h3>Completed</h3>
        <div class="value">{{ $reservations->where('ended', 1)->count() }}</div>
    </div>
    <div class="metric-card">
        <h3>Pending</h3>
        <div class="value">{{ $reservations->where('ended', 0)->where('canceled', 0)->count() }}</div>
    </div>
    <div class="metric-card">
        <h3>Canceled</h3>
        <div class="value">{{ $reservations->where('canceled', 1)->count() }}</div>
    </div>
</div>

<div class="filters">
    @if($filters['start_date'] || $filters['end_date'])
        <p><strong>Date Range:</strong> {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</p>
    @endif
    @if($filters['hotel'])
        <p><strong>Hotel:</strong> {{ $filters['hotel'] }}</p>
    @endif
    @if($filters['restaurant'])
        <p><strong>Restaurant:</strong> {{ $filters['restaurant'] }}</p>
    @endif
    @if($filters['meal_type'])
        <p><strong>Meal Type:</strong> {{ $filters['meal_type'] }}</p>
    @endif
    @if($filters['status'])
        <p><strong>Status:</strong> {{ ucfirst($filters['status']) }}</p>
    @endif
</div>

<table>
    <thead>
        <tr>
            @foreach($selectedColumns as $column)
                @switch($column)
                    @case('id')
                        <th>ID</th>
                        @break
                    @case('room')
                        <th>Room Number</th>
                        @break
                    @case('date')
                        <th>Date</th>
                        @break
                    @case('time')
                        <th>Time</th>
                        @break
                    @case('guest')
                        <th>Guest</th>
                        @break
                    @case('hotel')
                        <th>Hotel</th>
                        @break
                    @case('restaurant')
                        <th>Restaurant</th>
                        @break
                    @case('mealtype')
                        <th>Meal Type</th>
                        @break
                    @case('guests')
                        <th>Guests</th>
                        @break
                    @case('status')
                        <th>Status</th>
                        @break
                @endswitch
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($reservations as $reservation)
            <tr>
                @foreach($selectedColumns as $column)
                    @switch($column)
                        @case('id')
                            <td>{{ $reservation->reservations_id }}</td>
                            @break
                        @case('room')
                            <td>{{ $reservation->room_number ?? 'N/A' }}</td>
                            @break
                        @case('date')
                            <td>{{ $reservation->reservation_date ? date('Y-m-d', strtotime($reservation->reservation_date)) : ($reservation->day ?? 'N/A') }}</td>
                            @break
                        @case('time')
                            <td>{{ $reservation->reservation_time ? date('h:i A', strtotime($reservation->reservation_time)) : ($reservation->time ? date('h:i A', strtotime($reservation->time)) : 'N/A') }}</td>
                            @break
                        @case('guest')
                            <td>{{ $reservation->guestNames ?? 'N/A' }}</td>
                            @break
                        @case('hotel')
                            <td>{{ $reservation->hotel ? $reservation->hotel->name : 'N/A' }}</td>
                            @break
                        @case('restaurant')
                            <td>{{ $reservation->restaurant ? $reservation->restaurant->name : 'N/A' }}</td>
                            @break
                        @case('mealtype')
                            <td>{{ $reservation->mealType && $reservation->mealType->translation ? $reservation->mealType->translation->name : 'N/A' }}</td>
                            @break
                        @case('guests')
                            <td>{{ $reservation->people_count ?? $reservation->pax ?? 'N/A' }}</td>
                            @break
                        @case('status')
                            <td>
                                @if($reservation->canceled)
                                    <span class="status-badge status-canceled">Canceled</span>
                                @elseif($reservation->ended)
                                    <span class="status-badge status-completed">Completed</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            @break
                    @endswitch
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($selectedColumns) }}" class="text-center">No reservations found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<style>
    .metrics {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .metric-card {
        flex: 1;
        margin: 0 10px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
    }
    .metric-card h3 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #666;
    }
    .metric-card .value {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    .filters {
        margin-bottom: 20px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .filters p {
        margin: 5px 0;
        font-size: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }
    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .status-badge {
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }
    .status-canceled {
        background-color: #dc3545;
        color: white;
    }
    .status-completed {
        background-color: #28a745;
        color: white;
    }
    .status-pending {
        background-color: #ffc107;
        color: black;
    }
</style>
@endsection 