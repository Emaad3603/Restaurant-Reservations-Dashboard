@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Edit Pricing Time</h2>
    <form action="{{ route('admin.restaurants.pricing-times.update', [$restaurant->restaurants_id, $pricingTime->restaurant_pricing_times_id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="menus_id">Menu</label>
            <select name="menus_id" id="menus_id" class="form-control" required>
                <option value="">Select Menu</option>
                @foreach($menus as $menu)
                    <option value="{{ $menu->menus_id }}" {{ $pricingTime->menus_id == $menu->menus_id ? 'selected' : '' }}>{{ $menu->label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Pricing Type</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="per_person" value="per_person" {{ $pricingTime->per_person ? 'checked' : '' }}>
                <label class="form-check-label" for="per_person">Per Person</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="per_room" value="per_room" {{ $pricingTime->reservation_by_room ? 'checked' : '' }}>
                <label class="form-check-label" for="per_room">Per Room</label>
            </div>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $pricingTime->year }}-{{ $pricingTime->month }}-{{ $pricingTime->day }}" required>
        </div>
        <div class="form-group">
            <label for="time">Time</label>
            <input type="time" name="time" id="time" class="form-control" value="{{ $pricingTime->time }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ $pricingTime->price }}" required>
        </div>
        <div class="form-group">
            <label for="meal_type">Meal Type</label>
            <select name="meal_type" id="meal_type" class="form-control" required>
                <option value="">Select Meal Type</option>
                @foreach($mealTypes as $mealType)
                    <option value="{{ $mealType->label }}" {{ $pricingTime->meal_type == $mealType->label ? 'selected' : '' }}>{{ $mealType->label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="extra_seats">Extra Seats</label>
            <input type="number" name="extra_seats" id="extra_seats" class="form-control" min="0" value="{{ $pricingTime->extra_seats }}">
        </div>
        <div class="form-group">
            <label for="menu_url">Menu URL</label>
            <input type="text" name="menu_url" id="menu_url" class="form-control" value="{{ $pricingTime->menu_url }}">
        </div>
        <div class="form-group">
            <label for="calculate_price">Calculate Price</label>
            <input type="number" name="calculate_price" id="calculate_price" class="form-control" min="0" max="1" value="{{ $pricingTime->calculate_price }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Pricing Time</button>
        <a href="{{ route('admin.restaurants.pricing-times.index', $restaurant->restaurants_id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 