@extends('admin.layouts.app')

@section('title', 'Add New Meal Type - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Meal Type</h1>
        <a href="{{ route('admin.meal-types.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Meal Types
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.meal-types.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label') }}" required>
                    @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <input type="number" class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" value="{{ old('company_id') }}">
                    @error('company_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Meal Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 