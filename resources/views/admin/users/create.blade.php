@extends('admin.layouts.app')
@section('title', 'Add User')
@section('content')
<div class="container">
    <h1>Add User</h1>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label>Display Name</label>
            <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Super Admin?</label>
            <input type="hidden" name="admin" value="0">
            <input type="checkbox" name="admin" value="1" {{ old('admin') ? 'checked' : '' }}>
        </div>
        <h4>Privileges</h4>
        <div class="form-check">
            <input type="hidden" name="hotels_tab" value="0">
            <input class="form-check-input" type="checkbox" name="hotels_tab" value="1" {{ old('hotels_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Hotels Tab</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="currencies_tab" value="0">
            <input class="form-check-input" type="checkbox" name="currencies_tab" value="1" {{ old('currencies_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Company Variables</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="meal_types_tab" value="0">
            <input class="form-check-input" type="checkbox" name="meal_types_tab" value="1" {{ old('meal_types_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Meal Types Tab</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="restaurants_tab" value="0">
            <input class="form-check-input" type="checkbox" name="restaurants_tab" value="1" {{ old('restaurants_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Restaurants Tab</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="restaurant_times_tab" value="0">
            <input class="form-check-input" type="checkbox" name="restaurant_times_tab" value="1" {{ old('restaurant_times_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Restaurant Time & Price</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="menu_links_tab" value="0">
            <input class="form-check-input" type="checkbox" name="menu_links_tab" value="1" {{ old('menu_links_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Menu Links Tab</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="reservations_tab" value="0">
            <input class="form-check-input" type="checkbox" name="reservations_tab" value="1" {{ old('reservations_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Reservations Tab</label>
        </div>
        <div class="form-check">
            <input type="hidden" name="reports_tab" value="0">
            <input class="form-check-input" type="checkbox" name="reports_tab" value="1" {{ old('reports_tab') ? 'checked' : '' }}>
            <label class="form-check-label">Reports Tab</label>
        </div>
        <button class="btn btn-success mt-3" type="submit">Create</button>
    </form>
</div>
@endsection 