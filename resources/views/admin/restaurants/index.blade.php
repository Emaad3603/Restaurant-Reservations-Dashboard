@extends('admin.layouts.app')

@section('title', 'Restaurants - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Restaurants</h1>
        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Restaurant
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.restaurants.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="name" class="form-label">Restaurant Name</label>
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
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">
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
                            <th>Hotel</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->restaurants_id }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $restaurant->hotel->name ?? 'N/A' }}</td>
                                <td>{{ $restaurant->capacity ?? 'N/A' }}</td>
                                <td>
                                    @if($restaurant->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this restaurant?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No restaurants found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $restaurants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 