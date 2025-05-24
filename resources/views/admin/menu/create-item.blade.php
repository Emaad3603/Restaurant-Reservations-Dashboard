@extends('admin.layouts.app')

@section('title', 'Add Menu Item - ' . $restaurant->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Menu Item</h1>
        <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Menu
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-cup-hot me-1"></i> New Menu Item for Category: {{ $category->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.restaurants.menu.storeItem', [$restaurant->restaurants_id, $category->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <div class="form-text">Provide a brief description of the menu item, including key ingredients or flavors.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <div class="form-text">Optional. Maximum file size: 2MB. Recommended size: 500x500 pixels.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="active" name="active" {{ old('active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.restaurants.menu.index', $restaurant->restaurants_id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Menu Item</button>
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
                    <p>Menu items are the individual dishes or products that your restaurant offers.</p>
                    <p>Tips for creating effective menu items:</p>
                    <ul>
                        <li>Use descriptive names that clearly identify the item</li>
                        <li>Include key ingredients in the description</li>
                        <li>Consider adding information about dietary restrictions (vegetarian, gluten-free, etc.)</li>
                        <li>Use high-quality images when possible</li>
                        <li>Keep pricing consistent with your restaurant's positioning</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 