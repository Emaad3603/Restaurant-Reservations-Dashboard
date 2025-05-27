@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Add Pricing Time for {{ $restaurant->name }}</h2>
    <form action="{{ route('admin.restaurants.pricing-times.store', $restaurant->restaurants_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="menus_id">Menu</label>
            <select name="menus_id" id="menus_id" class="form-control" required>
                <option value="">Select Menu</option>
                @foreach($menus as $menu)
                    <option value="{{ $menu->menus_id }}">{{ $menu->label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Pricing Type</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="per_person" value="per_person" checked>
                <label class="form-check-label" for="per_person">Per Person</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pricing_type" id="per_room" value="per_room">
                <label class="form-check-label" for="per_room">Per Room</label>
            </div>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="time">Time</label>
            <input type="time" name="time" id="time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="meal_type">Meal Type</label>
            <select name="meal_type" id="meal_type" class="form-control" required>
                <option value="">Select Meal Type</option>
                @foreach($mealTypes as $mealType)
                    <option value="{{ $mealType->label }}">{{ $mealType->label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="extra_seats">Extra Seats</label>
            <input type="number" name="extra_seats" id="extra_seats" class="form-control" min="0" value="0">
        </div>
        <div class="form-group">
            <label for="menu_url">Menu URL</label>
            <input type="text" name="menu_url" id="menu_url" class="form-control">
        </div>
        <div class="form-group">
            <label for="calculate_price">Calculate Price</label>
            <input type="number" name="calculate_price" id="calculate_price" class="form-control" min="0" max="1" value="1">
        </div>
        <button type="submit" class="btn btn-primary">Add Pricing Time</button>
    </form>
</div>
@endsection 