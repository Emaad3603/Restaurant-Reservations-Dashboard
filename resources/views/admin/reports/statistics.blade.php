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
            <div class="card border-left-primary shadow h-100 py-2">
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
            <div class="card border-left-success shadow h-100 py-2">
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
            <div class="card border-left-info shadow h-100 py-2">
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
            <div class="card border-left-warning shadow h-100 py-2">
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
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-1"></i> Reservations by Meal Type
                    </h6>
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
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-1"></i> Reservations by Restaurant
                    </h6>
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
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-1"></i> Reservations by Day of Week
                    </h6>
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
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-xs {
        font-size: 0.7rem;
    }
    .chart-pie, .chart-bar, .chart-line {
        position: relative;
        height: 300px;
    }
    .small {
        font-size: 0.875rem;
    }
    .fas.fa-circle {
        font-size: 0.75rem;
    }
</style>

<style media="print">
    @page {
        size: A4;
        margin: 1cm;
    }
    body {
        padding: 0;
        margin: 0;
        font-size: 12pt;
        line-height: 1.5;
        color: #000 !important;
        background: #fff !important;
    }
    .container-fluid {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    .sidebar, .navbar, .btn, footer {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        break-inside: avoid;
        margin-bottom: 20px;
        page-break-inside: avoid;
    }
    .card-header {
        background-color: #f8f9fc !important;
        border-bottom: 2px solid #000 !important;
        padding: 10px !important;
    }
    .card-body {
        padding: 15px !important;
    }
    .border-left-primary, .border-left-success, .border-left-info, .border-left-warning {
        border-left: 2px solid #000 !important;
    }
    .text-primary, .text-success, .text-info, .text-warning {
        color: #000 !important;
    }
    .text-gray-300 {
        color: #666 !important;
    }
    .text-gray-800 {
        color: #000 !important;
    }
    .text-xs {
        font-size: 10pt !important;
    }
    .h5 {
        font-size: 14pt !important;
        font-weight: bold !important;
    }
    .chart-pie, .chart-bar, .chart-line {
        position: relative;
        height: 300px;
        page-break-inside: avoid;
    }
    canvas {
        max-width: 100% !important;
        height: auto !important;
        page-break-inside: avoid;
    }
    .small {
        font-size: 11pt !important;
    }
    .fas.fa-circle {
        font-size: 10pt !important;
    }
    .row {
        display: block !important;
    }
    .col-xl-3, .col-xl-4, .col-lg-6, .col-lg-12 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }
    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    .mr-2 {
        margin-right: 0.5rem !important;
    }
    .col-auto {
        display: none !important;
    }
    .fs-2 {
        font-size: 1.5rem !important;
    }
    .text-uppercase {
        text-transform: uppercase !important;
    }
    .font-weight-bold {
        font-weight: bold !important;
    }
    .no-gutters {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }
    .align-items-center {
        align-items: center !important;
    }
    .justify-content-between {
        justify-content: space-between !important;
    }
    .d-flex {
        display: flex !important;
    }
    .flex-row {
        flex-direction: row !important;
    }
    .align-items-center {
        align-items: center !important;
    }
    .me-1 {
        margin-right: 0.25rem !important;
    }
    .mt-4 {
        margin-top: 1.5rem !important;
    }
    .text-center {
        text-align: center !important;
    }
    .mr-2 {
        margin-right: 0.5rem !important;
    }
    .fas {
        font-family: "Font Awesome 5 Free" !important;
        font-weight: 900 !important;
    }
    .bi {
        font-family: "Bootstrap Icons" !important;
    }
    .bi-pie-chart:before {
        content: "\f200" !important;
    }
    .bi-bar-chart:before {
        content: "\f201" !important;
    }
    .bi-graph-up:before {
        content: "\f202" !important;
    }
    .bi-calendar-check:before {
        content: "\f274" !important;
    }
    .bi-calendar-day:before {
        content: "\f783" !important;
    }
    .bi-calendar-week:before {
        content: "\f784" !important;
    }
    .bi-calendar-month:before {
        content: "\f785" !important;
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
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
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
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
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
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush

@endsection 