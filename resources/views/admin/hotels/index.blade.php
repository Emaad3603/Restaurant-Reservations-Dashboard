@extends('admin.layouts.app')

@section('title', 'Hotels - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hotels</h1>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Hotel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Company ID</th>
                            <th>Status</th>
                            <th>Restaurants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                            <tr>
                                <td>{{ $hotel->hotel_id }}</td>
                                <td>{{ $hotel->name }}</td>
                                <td>{{ $hotel->company_id ?? 'N/A' }}</td>
                                <td>
                                    @if($hotel->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $hotel->restaurants_count ?? $hotel->restaurants->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.hotels.show', $hotel) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this hotel?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hotels found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 