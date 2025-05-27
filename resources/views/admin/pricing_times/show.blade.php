@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Pricing Time Details</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Date:</strong> {{ $pricingTime->year }}-{{ $pricingTime->month }}-{{ $pricingTime->day }}</p>
            <p><strong>Time:</strong> {{ $pricingTime->time }}</p>
            <p><strong>Price:</strong> {{ $pricingTime->price }}</p>
            <p><strong>Menu:</strong> {{ optional(App\Models\Menu::find($pricingTime->menus_id))->label ?? '-' }}</p>
            <p><strong>Meal Type:</strong> {{ $pricingTime->meal_type }}</p>
            <p><strong>Type:</strong>
                @if($pricingTime->per_person)
                    Per Person
                @elseif($pricingTime->reservation_by_room)
                    Per Room
                @else
                    -
                @endif
            </p>
            <p><strong>Extra Seats:</strong> {{ $pricingTime->extra_seats }}</p>
            <p><strong>Menu URL:</strong> {{ $pricingTime->menu_url }}</p>
            <p><strong>Calculate Price:</strong> {{ $pricingTime->calculate_price ? 'Yes' : 'No' }}</p>
        </div>
    </div>
    <a href="{{ route('admin.restaurants.pricing-times.index', $restaurant->restaurants_id) }}" class="btn btn-secondary mt-3">Back to Pricing Times</a>
</div>
@endsection 