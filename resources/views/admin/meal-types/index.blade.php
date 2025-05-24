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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Time</th>
                            <th>Hotel/Restaurant</th>
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
                                    @if($mealType->start_time && $mealType->end_time)
                                        {{ date('h:i A', strtotime($mealType->start_time)) }} - {{ date('h:i A', strtotime($mealType->end_time)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($mealType->hotel)
                                        <span class="d-block">Hotel: {{ $mealType->hotel->name }}</span>
                                    @endif
                                    @if($mealType->restaurant)
                                        <span class="d-block">Restaurant: {{ $mealType->restaurant->name }}</span>
                                    @endif
                                </td>
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