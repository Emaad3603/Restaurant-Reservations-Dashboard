@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Menu for Pricing Time ({{ $pricingTime->year }}-{{ $pricingTime->month }}-{{ $pricingTime->day }} {{ $pricingTime->time }})</h2>
    @if($menu)
        <div class="card mb-3">
            <div class="card-body">
                <h4>{{ $menu->label }}</h4>
                <p>Menu ID: {{ $menu->menus_id }}</p>
                <h5>Categories & Items</h5>
                @foreach($menu->categories as $category)
                    <div class="mb-2">
                        <strong>{{ $category->label }}</strong>
                        @php
                            $items = $menu->items;
                        @endphp
                        @if($items->count())
                            <ul>
                                @foreach($items as $item)
                                    <li>{{ $item->label ?? $item->items_id }} - {{ $item->price }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No items in this category.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning">No menu found for this pricing time.</div>
    @endif
    <a href="{{ route('admin.restaurants.pricing-times.index', $restaurant->restaurants_id) }}" class="btn btn-secondary">Back to Pricing Times</a>
</div>
@endsection 