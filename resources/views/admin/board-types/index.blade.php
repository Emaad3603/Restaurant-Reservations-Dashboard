@extends('admin.layouts.app')

@section('title', 'Board Types - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Board Types</h1>
        <a href="{{ route('admin.board-types.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Board Type
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.board-types.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="board_name" class="form-label">Board Name</label>
                    <input type="text" class="form-control" id="board_name" name="board_name" value="{{ request('board_name') }}" placeholder="Search by board name">
                </div>
                <div class="col-md-3">
                    <label for="board_id" class="form-label">Board ID</label>
                    <input type="text" class="form-control" id="board_id" name="board_id" value="{{ request('board_id') }}" placeholder="Search by board ID">
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
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-select" id="company_id" name="company_id">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->company_id }}" {{ request('company_id') == $company->company_id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="free_count" class="form-label">Free Count</label>
                    <input type="number" class="form-control" id="free_count" name="free_count" value="{{ request('free_count') }}" min="0" placeholder="Filter by free count">
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.board-types.index') }}" class="btn btn-secondary">
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
                            <th>Board Name</th>
                            <th>Board ID</th>
                            <th>Company</th>
                            <th>Hotel</th>
                            <th>Free Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boardTypes as $boardType)
                            <tr>
                                <td>{{ $boardType->board_type_rules_id }}</td>
                                <td>{{ $boardType->board_name }}</td>
                                <td>{{ $boardType->board_id }}</td>
                                <td>{{ $boardType->company->company_name ?? 'N/A' }}</td>
                                <td>{{ $boardType->hotel->name ?? 'N/A' }}</td>
                                <td>{{ $boardType->free_count }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.board-types.show', $boardType) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.board-types.edit', $boardType) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.board-types.destroy', $boardType) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this board type?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No board types found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $boardTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 