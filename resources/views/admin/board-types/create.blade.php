@extends('admin.layouts.app')

@section('title', 'Add New Board Type - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Board Type</h1>
        <a href="{{ route('admin.board-types.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Board Types
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.board-types.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="board_name" class="form-label">Board Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('board_name') is-invalid @enderror" id="board_name" name="board_name" value="{{ old('board_name') }}" required>
                            @error('board_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="board_id" class="form-label">Board ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('board_id') is-invalid @enderror" id="board_id" name="board_id" value="{{ old('board_id') }}" required>
                            @error('board_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->company_id }}" {{ old('company_id') == $company->company_id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hotel_id" class="form-label">Hotel</label>
                            <select class="form-select @error('hotel_id') is-invalid @enderror" id="hotel_id" name="hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->hotel_id }}" {{ old('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="free_count" class="form-label">Free Count <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('free_count') is-invalid @enderror" id="free_count" name="free_count" value="{{ old('free_count', 0) }}" min="0" required>
                            @error('free_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Board Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 