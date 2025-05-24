@extends('admin.layouts.app')

@section('title', 'Meal Type Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Meal Type Details</h1>
        <div>
            <a href="{{ route('admin.meal-types.edit', $mealType) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Meal Type
            </a>
            <a href="{{ route('admin.meal-types.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Meal Types
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock me-1"></i> Meal Type Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">ID</div>
                        <div class="col-md-9">{{ $mealType->meal_types_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Name</div>
                        <div class="col-md-9">{{ $mealType->translation->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Description</div>
                        <div class="col-md-9">{{ $mealType->translation->description ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Time Range</div>
                        <div class="col-md-9">
                            @if($mealType->start_time && $mealType->end_time)
                                {{ date('h:i A', strtotime($mealType->start_time)) }} - {{ date('h:i A', strtotime($mealType->end_time)) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Hotel</div>
                        <div class="col-md-9">
                            @if($mealType->hotel)
                                <a href="{{ route('admin.hotels.show', $mealType->hotel) }}">
                                    {{ $mealType->hotel->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Restaurant</div>
                        <div class="col-md-9">
                            @if($mealType->restaurant)
                                <a href="{{ route('admin.restaurants.show', $mealType->restaurant) }}">
                                    {{ $mealType->restaurant->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status</div>
                        <div class="col-md-9">
                            @if($mealType->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($mealType->icon)
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Icon</div>
                            <div class="col-md-9">
                                @if(Str::startsWith($mealType->icon, ['http://', 'https://']))
                                    <img src="{{ $mealType->icon }}" alt="Icon" class="img-thumbnail" style="max-width: 50px;">
                                @else
                                    <i class="{{ $mealType->icon }}" style="font-size: 2rem;"></i>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created</div>
                        <div class="col-md-9">{{ $mealType->created_at }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Last Updated</div>
                        <div class="col-md-9">{{ $mealType->updated_at }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-translate me-1"></i> Translations
                    </h5>
                </div>
                <div class="card-body">
                    @if($mealType->translations->count() > 0)
                        <ul class="list-group">
                            @foreach($mealType->translations as $translation)
                                <li class="list-group-item">
                                    <div><strong>Locale:</strong> {{ $translation->locale }}</div>
                                    <div><strong>Name:</strong> {{ $translation->name }}</div>
                                    @if($translation->description)
                                        <div><strong>Description:</strong> {{ $translation->description }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No translations found for this meal type.</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i> Reservations
                    </h5>
                </div>
                <div class="card-body">
                    @if($mealType->reservations && $mealType->reservations->count() > 0)
                        <ul class="list-group">
                            @foreach($mealType->reservations->take(5) as $reservation)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Reservation #{{ $reservation->reservations_id }}
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if($mealType->reservations->count() > 5)
                            <div class="mt-2 text-center">
                                <a href="{{ route('admin.reports.reservations', ['meal_type_id' => $mealType->meal_types_id]) }}" class="btn btn-sm btn-primary">
                                    View All {{ $mealType->reservations->count() }} Reservations
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No reservations found for this meal type.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 