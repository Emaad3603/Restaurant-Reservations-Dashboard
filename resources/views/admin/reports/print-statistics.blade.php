@extends('admin.reports.print-layout')

@section('content')
<div class="metrics">
    <div class="metric-card">
        <h3>Total Reservations</h3>
        <div class="value">{{ number_format($totalReservations) }}</div>
    </div>
    <div class="metric-card">
        <h3>Today's Reservations</h3>
        <div class="value">{{ number_format($todayReservations) }}</div>
    </div>
    <div class="metric-card">
        <h3>This Week's Reservations</h3>
        <div class="value">{{ number_format($weekReservations) }}</div>
    </div>
    <div class="metric-card">
        <h3>This Month's Reservations</h3>
        <div class="value">{{ number_format($monthReservations) }}</div>
    </div>
</div>

<div class="chart-container">
    <h2>Reservations by Meal Type</h2>
    <table>
        <thead>
            <tr>
                <th>Meal Type</th>
                <th>Total Reservations</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservationsByMealType as $mealType)
                <tr>
                    <td>{{ $mealType->name }}</td>
                    <td>{{ $mealType->total }}</td>
                    <td>{{ number_format(($mealType->total / $totalReservations) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="chart-container">
    <h2>Reservations by Restaurant</h2>
    <table>
        <thead>
            <tr>
                <th>Restaurant</th>
                <th>Total Reservations</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservationsByRestaurant as $restaurant)
                <tr>
                    <td>{{ $restaurant->name }}</td>
                    <td>{{ $restaurant->total }}</td>
                    <td>{{ number_format(($restaurant->total / $totalReservations) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="chart-container">
    <h2>Reservations by Day of Week</h2>
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Total Reservations</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservationsByDay as $day)
                <tr>
                    <td>{{ $day->day }}</td>
                    <td>{{ $day->total }}</td>
                    <td>{{ number_format(($day->total / $totalReservations) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 