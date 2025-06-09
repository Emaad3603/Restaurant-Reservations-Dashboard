@extends('admin.layouts.app')

@section('title', 'Company Details - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Company Details</h1>
        <div>
            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Company
            </a>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Companies
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Company ID</div>
                <div class="col-md-9">{{ $company->company_id }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Name</div>
                <div class="col-md-9">{{ $company->company_name }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Currency ID</div>
                <div class="col-md-9">{{ $company->currency_id }}</div>
            </div>
            @if($company->logo_url)
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Logo</div>
                    <div class="col-md-9">
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }} Logo" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
            @endif
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">UUID</div>
                <div class="col-md-9">{{ $company->company_uuid }}</div>
            </div>
        </div>
    </div>
</div>
@endsection 