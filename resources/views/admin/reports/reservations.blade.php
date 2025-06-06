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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-1"></i> Filter Reservations
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.reservations') }}" class="mb-0">
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#columnSelectModal">
                <i class="bi bi-layout-three-columns"></i> Select Columns
            </button>
        </div>
    </div>

    <!-- Column Selection Modal -->
    <div class="modal fade" id="columnSelectModal" tabindex="-1" aria-labelledby="columnSelectModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="columnSelectModalLabel">Select Columns to Display/Print</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="columnSelectForm">
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="0" id="col-id" checked>
                <label class="form-check-label" for="col-id">ID</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="1" id="col-room" checked>
                <label class="form-check-label" for="col-room">Room Number</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="2" id="col-date" checked>
                <label class="form-check-label" for="col-date">Date</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="3" id="col-time" checked>
                <label class="form-check-label" for="col-time">Time</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="4" id="col-guest" checked>
                <label class="form-check-label" for="col-guest">Guest</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="5" id="col-hotel" checked>
                <label class="form-check-label" for="col-hotel">Hotel</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="6" id="col-restaurant" checked>
                <label class="form-check-label" for="col-restaurant">Restaurant</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="7" id="col-mealtype" checked>
                <label class="form-check-label" for="col-mealtype">Meal Type</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="8" id="col-guests" checked>
                <label class="form-check-label" for="col-guests">Guests</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="9" id="col-status" checked>
                <label class="form-check-label" for="col-status">Status</label>
              </div>
              <div class="form-check">
                <input class="form-check-input column-toggle" type="checkbox" value="10" id="col-actions" checked>
                <label class="form-check-label" for="col-actions">Actions</label>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-1"></i> Reservations List
            </h6>
            <div class="text-muted small">
                Total Records: {{ $reservations->total() }}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
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
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No reservations found
                                    </div>
                                </td>
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

<style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .page-link {
        color: var(--primary-color);
    }
    .page-link:hover {
        color: var(--secondary-color);
    }
</style>

<style media="print">
    @page {
        size: A4 landscape;
        margin: 0;
    }
    html, body, .container-fluid, .card, .card-body, .card-header, .table-responsive, table {
        margin: 0 !important;
        padding: 0 !important;
        width: 100vw !important;
        min-width: 0 !important;
        max-width: none !important;
        box-sizing: border-box !important;
        background: #fff !important;
    }
    html, body {
        font-size: 12pt;
        line-height: 1.5;
        color: #000 !important;
        font-family: Arial, Helvetica, sans-serif !important;
    }
    .sidebar, .navbar, .btn, footer, .pagination, form[action*="reports"] {
        display: none !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
    table.table, table {
        border-collapse: collapse !important;
        border: 1.5px solid #222 !important;
        background: #fff !important;
        table-layout: auto !important;
        width: 100vw !important;
    }
    table.table th, table.table td, table th, table td {
        border: 1.5px solid #222 !important;
        padding: 6px 8px !important;
        font-size: 11pt !important;
        color: #000 !important;
        background: #fff !important;
        text-align: center !important;
        vertical-align: middle !important;
        white-space: normal !important;
        word-break: break-word !important;
    }
    table.table th, table th {
        font-weight: bold !important;
        font-size: 12pt !important;
        background: #f8f8f8 !important;
        text-align: center !important;
        border-bottom: 2px solid #222 !important;
    }
    .status-ended, .status-canceled, .status-cancelled {
        color: #d00 !important;
        font-weight: bold !important;
    }
    .status-completed, .status-confirmed, .status-success {
        color: #080 !important;
        font-weight: bold !important;
    }
    .cancelled-yes {
        color: #d00 !important;
        font-weight: bold !important;
    }
    .cancelled-no {
        color: #080 !important;
        font-weight: bold !important;
    }
    .guest-names {
        white-space: pre-line !important;
        text-align: left !important;
        font-size: 11pt !important;
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
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateTableColumns() {
        document.querySelectorAll('.table thead th, .table tbody td').forEach(function(cell) {
            cell.style.display = '';
        });
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            var colIdx = parseInt(checkbox.value);
            var display = checkbox.checked ? '' : 'none';
            document.querySelectorAll('.table tr').forEach(function(row) {
                if (row.children[colIdx]) {
                    row.children[colIdx].style.display = display;
                }
            });
        });
    }
    document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTableColumns);
    });
    // Initial update in case of saved state
    updateTableColumns();
});
</script>
@endpush

@endsection 