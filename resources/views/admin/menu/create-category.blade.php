@extends('admin.layouts.app')

@section('title', 'Add Menu Category - ' . $restaurant->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Menu Category</h1>
        <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Menu
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-tag me-1"></i> New Menu Category for {{ $restaurant->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.restaurants.menu.storeCategory', $restaurant->restaurants_id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-1"></i> Information
                    </h5>
                </div>
                <div class="card-body">
                    <p>Menu categories help organize your restaurant's menu items into logical sections.</p>
                    <p>Examples of common menu categories:</p>
                    <ul>
                        <li>Appetizers</li>
                        <li>Main Courses</li>
                        <li>Desserts</li>
                        <li>Beverages</li>
                        <li>Specials</li>
                    </ul>
                    <p>After creating a category, you can add menu items to it.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 