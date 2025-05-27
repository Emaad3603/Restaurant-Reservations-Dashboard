@extends('admin.layouts.app')
@section('title', 'User Management')
@section('content')
<div class="container">
    <h1>User Management</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add User</a>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Company Variables</th>
                <th>Restaurant Time & Price</th>
                <th>Hotels</th>
                <th>Restaurants</th>
                <th>Meal Types</th>
                <th>Menu Links</th>
                <th>Reservations Tab</th>
                <th>Reports Tab</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->user_name }}</td>
                <td>********</td>
                <td class="{{ $user->privilege?->currencies_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->currencies_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->restaurant_times_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->restaurant_times_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->hotels_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->hotels_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->restaurants_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->restaurants_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->meal_types_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->meal_types_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->menu_links_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->menu_links_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->reservations_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->reservations_tab ? 'YES' : 'NO' }}
                </td>
                <td class="{{ $user->privilege?->reports_tab ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $user->privilege?->reports_tab ? 'YES' : 'NO' }}
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection 