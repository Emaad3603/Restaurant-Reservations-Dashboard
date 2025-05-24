@extends('admin.layouts.app')

@section('title', 'Edit Menu Category - ' . $restaurant->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Menu Category</h1>
        <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Menu
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-tag me-1"></i> Edit: {{ $category->label }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.restaurants.menu.updateCategory', [$restaurant->restaurants_id, $category->menu_categories_id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->label) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->background_url) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-1"></i> Category Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong> {{ $category->updated_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Items in Category:</strong> {{ $category->menuItems->count() }}
                    </div>
                    
                    @if($category->menuItems->count() > 0)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-1"></i> This category contains menu items. If you make it inactive, the items will still exist but won't be visible to customers.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i> This category has no menu items yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 