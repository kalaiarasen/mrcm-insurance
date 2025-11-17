@extends('layouts.main')

@section('title', 'Claims Management')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        @hasanyrole('Super Admin|Admin|Agent')
                            Claims Management
                        @else
                            My Claims
                        @endhasanyrole
                    </h3>
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
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Claims</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Claims List</h5>
                                <p class="f-m-light mt-1">
                                    @hasanyrole('Super Admin|Admin|Agent')
                                        Manage all insurance claims
                                    @else
                                        View and manage your insurance claims
                                    @endhasanyrole
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th>Claim ID</th>
                                            @hasanyrole('Super Admin|Admin|Agent')
                                            <th>User</th>
                                            @endhasanyrole
                                            <th>Claim Title</th>
                                            <th>Policy</th>
                                            <th>Incident Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Documents</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($claims as $claim)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $claim->id }}</strong>
                                                </td>
                                                @hasanyrole('Super Admin|Admin|Agent')
                                                <td>
                                                    @if ($claim->user)
                                                        {{ $claim->user->name }}
                                                        <br>
                                                        <small class="text-muted">{{ $claim->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                @endhasanyrole
                                                <td>
                                                    <strong>{{ $claim->claim_title }}</strong>
                                                </td>
                                                <td>
                                                    @if ($claim->policyApplication)
                                                        <small class="text-muted">
                                                            {{ $claim->policyApplication->reference_number ?? 'N/A' }}
                                                        </small>
                                                    @else
                                                        <span class="badge bg-secondary">No Policy</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $claim->incident_date?->format('d M Y') ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                            'closed' => 'secondary',
                                                        ];
                                                        $statusColor = $statusColors[$claim->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColor }}">
                                                        {{ ucfirst($claim->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($claim->claim_amount)
                                                        <strong>RM {{ number_format($claim->claim_amount, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $claim->claimDocuments->count() }} file(s)
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $claim->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <ul class="action">
                                                        <li class="view">
                                                            <a href="{{ route('claims.show', $claim->id) }}" title="View Details">
                                                                <i class="fa-regular fa-eye"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datatable").DataTable({
                "order": [[8, "desc"]] // Sort by Created Date column
            });
        });

        function downloadClaimZip(claimId) {
            // TODO: Implement bulk download of claim documents as ZIP
            alert('Download feature coming soon!');
        }
    </script>
@endsection
