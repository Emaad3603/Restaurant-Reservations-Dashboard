@extends('admin.layouts.app')

@section('title', 'Meal Types - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Meal Types</h1>
        <a href="{{ route('admin.meal-types.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Meal Type
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.meal-types.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="name" class="form-label">Meal Type Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}" placeholder="Search by name">
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
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.meal-types.index') }}" class="btn btn-secondary">
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
                            <th>Name</th>
                            <th>Status</th>
                            <th>Reservations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mealTypes as $mealType)
                            <tr>
                                <td>{{ $mealType->meal_types_id }}</td>
                                <td>{{ $mealType->translation->name }}</td>
                                <td>
                                    @if($mealType->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $mealType->reservations_count }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.meal-types.show', $mealType) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.meal-types.edit', $mealType) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.meal-types.destroy', $mealType) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this meal type?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No meal types found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $mealTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 