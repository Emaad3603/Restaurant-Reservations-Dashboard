@extends('admin.layouts.app')

@section('title', 'Board Type Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Board Type Details</h1>
        <div>
            <a href="{{ route('admin.board-types.edit', $boardType) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.board-types.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Board Types
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mb-4">Basic Information</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td>{{ $boardType->board_type_rules_id }}</td>
                        </tr>
                        <tr>
                            <th>Board Name</th>
                            <td>{{ $boardType->board_name }}</td>
                        </tr>
                        <tr>
                            <th>Board ID</th>
                            <td>{{ $boardType->board_id }}</td>
                        </tr>
                        <tr>
                            <th>Free Count</th>
                            <td>{{ $boardType->free_count }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title mb-4">Relationships</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Company</th>
                            <td>{{ $boardType->company->company_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Hotel</th>
                            <td>{{ $boardType->hotel->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="card-title mb-4">Timestamps</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Created At</th>
                            <td>{{ $boardType->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $boardType->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 