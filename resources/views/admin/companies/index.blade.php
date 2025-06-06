@extends('admin.layouts.app')

@section('title', 'Companies - Restaurant Reservations')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Companies</h1>
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Company
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Currency</th>
                            <th>UUID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>
                                    @if($company->logo_url)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }} Logo" class="img-thumbnail" style="max-width: 50px;">
                                    @else
                                        <span class="text-muted">No Logo</span>
                                    @endif
                                </td>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->currency->name ?? 'N/A' }}</td>
                                <td>{{ $company->company_uuid }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this company?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No companies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 