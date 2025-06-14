@extends('admin.layouts.app')

@section('title', $restaurant->name . ' - Pricing Times Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $restaurant->name }} - Pricing Times Management</h1>
        <div>
            <a href="{{ route('admin.restaurants.pricing-times.create', $restaurant->restaurants_id) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Pricing Time
            </a>
            <a href="{{ route('admin.restaurants.show', $restaurant->restaurants_id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Restaurant
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Price</th>
                <th>Meal Type</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pricingTimes as $pt)
            <tr>
                <td>{{ $pt->year }}-{{ $pt->month }}-{{ $pt->day }}</td>
                <td>{{ $pt->time }}</td>
                <td>{{ $pt->price }}</td>
                <td>{{ $pt->meal_type }}</td>
                <td>
                    @if($pt->per_person)
                        Per Person
                    @elseif($pt->reservation_by_room)
                        Per Room
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.restaurants.pricing-times.menu', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" class="btn btn-sm btn-success">View Menu</a>
                    <a href="{{ route('admin.restaurants.pricing-times.edit', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.restaurants.pricing-times.destroy', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 