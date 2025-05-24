@extends('admin.layouts.app')

@section('title', $restaurant->name . ' - Menu Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $restaurant->name }} - Menu Management</h1>
        <div>
            <a href="{{ route('admin.restaurants.menu.createCategory', $restaurant->restaurants_id) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Category
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

    @if($categories->isEmpty())
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-exclamation-circle fs-1 text-muted mb-3"></i>
                <h5>No Menu Categories</h5>
                <p class="text-muted">This restaurant doesn't have any menu categories yet.</p>
                <a href="{{ route('admin.restaurants.menu.createCategory', $restaurant->restaurants_id) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add First Category
                </a>
            </div>
        </div>
    @else
        @foreach($categories as $category)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-tag me-1"></i> {{ $category->label }}
                </h5>
                <div>
                    <a href="{{ route('admin.restaurants.menu.createItem', [$restaurant->restaurants_id, $category->menu_categories_id]) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Add Item
                    </a>
                    <a href="{{ route('admin.restaurants.menu.editCategory', [$restaurant->restaurants_id, $category->menu_categories_id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.restaurants.menu.destroyCategory', [$restaurant->restaurants_id, $category->menu_categories_id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($category->background_url)
                    <p class="text-muted mb-3">{{ $category->background_url }}</p>
                @endif

                @if($category->menuItems->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">No menu items in this category.</p>
                        <a href="{{ route('admin.restaurants.menu.createItem', [$restaurant->restaurants_id, $category->menu_categories_id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add Item
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 80px">Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 120px">Price</th>
                                    <th style="width: 80px">Status</th>
                                    <th style="width: 120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->menuItems as $item)
                                <tr>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="bi bi-image" style="font-size: 30px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        @if($item->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.restaurants.menu.editItem', [$restaurant->restaurants_id, $item->menu_items_id]) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.restaurants.menu.destroyItem', [$restaurant->restaurants_id, $item->menu_items_id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection 