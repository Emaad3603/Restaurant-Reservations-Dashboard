@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Pricing Times for {{ $restaurant->name }}</h2>
    <a href="{{ route('admin.restaurants.pricing-times.create', $restaurant->restaurants_id) }}" class="btn btn-primary mb-3">Add Pricing Time</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Price</th>
                <th>Menu</th>
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
                <td>{{ optional(App\Models\Menu::find($pt->menus_id))->label ?? '-' }}</td>
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
                    <a href="{{ route('admin.restaurants.pricing-times.show', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" class="btn btn-sm btn-info">Show</a>
                    <a href="{{ route('admin.restaurants.pricing-times.edit', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.restaurants.pricing-times.destroy', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @if($pt->menus_id)
                        <a href="{{ route('admin.restaurants.pricing-times.menu', [$restaurant->restaurants_id, $pt->restaurant_pricing_times_id]) }}" class="btn btn-sm btn-success">View Menu</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 