@extends('admin.layouts.app')

@section('title', 'Reservation Statistics - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservation Statistics</h1>
        <div>
            <a href="{{ route('admin.reports.reservations') }}" class="btn btn-primary">
                <i class="bi bi-table me-1"></i> Detailed Reports
            </a>
            <button onclick="window.print();" class="btn btn-info">
                <i class="bi bi-printer me-1"></i> Print Statistics
            </button>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalReservations) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($todayReservations) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-day fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Week's Reservations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($weekReservations) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-week fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                This Month's Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthReservations) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-month fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <!-- Meal Type Pie Chart -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Reservations by Meal Type</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="mealTypePieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($reservationsByMealType as $index => $mealType)
                            <span class="mr-2">
                                <i class="fas fa-circle" style="color: {{ 'hsl(' . ($index * 60) . ', 70%, 60%)' }}"></i> {{ $mealType->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Restaurant Bar Chart -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Reservations by Restaurant</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="restaurantBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Day of Week Line Chart -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Reservations by Day of Week</h6>
                </div>
                <div class="card-body">
                    <div class="chart-line">
                        <canvas id="dayLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style media="print">
    .sidebar, .navbar, .btn, footer {
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
    .card {
        border: none !important;
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data
    var mealTypeData = <?php echo json_encode($reservationsByMealType); ?>;
    var restaurantData = <?php echo json_encode($reservationsByRestaurant); ?>;
    var dayData = <?php echo json_encode($reservationsByDay); ?>;
    
    // Meal Type Pie Chart
    var mealTypeCtx = document.getElementById('mealTypePieChart').getContext('2d');
    var mealTypeLabels = [];
    var mealTypeValues = [];
    var mealTypeColors = [];
    
    for (var i = 0; i < mealTypeData.length; i++) {
        mealTypeLabels.push(mealTypeData[i].name);
        mealTypeValues.push(mealTypeData[i].total);
        mealTypeColors.push('hsl(' + (i * 60) + ', 70%, 60%)');
    }
    
    new Chart(mealTypeCtx, {
        type: 'pie',
        data: {
            labels: mealTypeLabels,
            datasets: [{
                data: mealTypeValues,
                backgroundColor: mealTypeColors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Restaurant Bar Chart
    var restaurantCtx = document.getElementById('restaurantBarChart').getContext('2d');
    var restaurantLabels = [];
    var restaurantValues = [];
    
    for (var i = 0; i < restaurantData.length; i++) {
        restaurantLabels.push(restaurantData[i].name);
        restaurantValues.push(restaurantData[i].total);
    }
    
    new Chart(restaurantCtx, {
        type: 'bar',
        data: {
            labels: restaurantLabels,
            datasets: [{
                label: 'Reservations',
                data: restaurantValues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Day of Week Line Chart
    var dayCtx = document.getElementById('dayLineChart').getContext('2d');
    var dayLabels = [];
    var dayValues = [];
    
    for (var i = 0; i < dayData.length; i++) {
        dayLabels.push(dayData[i].day);
        dayValues.push(dayData[i].total);
    }
    
    new Chart(dayCtx, {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Reservations',
                data: dayValues,
                fill: false,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

@endsection 