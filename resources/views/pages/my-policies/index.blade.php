@extends('layouts.main')

@section('title', 'My Policies')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>My Policies</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">My Policies</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Professional Indemnity Policies -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>🛡️ Professional Indemnity Policies</h5>
                        <p class="f-m-light mt-1">Your professional indemnity insurance applications</p>
                    </div>
                    <div class="card-body">
                        @if ($policyApplications->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No professional indemnity policies found</p>
                                <a href="{{ route('new-policy') }}" class="btn btn-primary mt-2">
                                    <i class="fa fa-plus"></i> Apply for New Policy
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Reference #</th>
                                            <th>Submitted Date</th>
                                            <th>Policy Period</th>
                                            <th>Premium</th>
                                            <th>Customer Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($policyApplications as $policy)
                                            <tr>
                                                <td>
                                                    <strong>{{ $policy->reference_number ?? 'Pending' }}</strong>
                                                </td>
                                                <td>{{ $policy->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    @if ($policy->policyPricing)
                                                        {{ \Carbon\Carbon::parse($policy->policyPricing->policy_start_date)->format('M d, Y') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($policy->policyPricing->policy_expiry_date)->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($policy->policyPricing)
                                                        <strong>RM
                                                            {{ number_format($policy->policyPricing->total_payable, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $customerStatusBadges = [
                                                            'submitted' => 'bg-secondary',
                                                            'pay_now' => 'bg-danger',
                                                            'paid' => 'bg-info',
                                                            'processing' => 'bg-warning',
                                                            'active' => 'bg-success',
                                                            'rejected' => 'bg-danger',
                                                        ];
                                                        $badge =
                                                            $customerStatusBadges[$policy->customer_status] ??
                                                            'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $badge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $policy->customer_status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('client-policy.show', $policy->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Policies (Quotation Requests) -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>📋 Other Policies</h5>
                        <p class="f-m-light mt-1">Track your insurance other policies requests</p>
                    </div>
                    <div class="card-body">
                        @if ($quotationRequests->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No other policies found</p>
                                <a href="{{ route('customer.products.index') }}" class="btn btn-primary mt-2">
                                    <i class="fa fa-search"></i> Browse Other Insurances
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Submitted Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotationRequests as $request)
                                            <tr>
                                                <td><strong>#{{ $request->id }}</strong></td>
                                                <td>{{ $request->product->title }}</td>
                                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge {{ $request->status_badge }}">
                                                        {{ $request->status_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.quotations.show', $request->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
