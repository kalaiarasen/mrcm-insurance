@extends('layouts.main')

@section('title', 'For Your Action')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        #dateFilterCollapse .card-body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        #dateFilterCollapse .form-control,
        #dateFilterCollapse .form-select {
            border: 2px solid #e3e6ea;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        #dateFilterCollapse .form-control:focus,
        #dateFilterCollapse .form-select:focus {
            border-color: #3D9FD8;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            transform: translateY(-1px);
        }

        #dateFilterCollapse .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(25, 135, 84, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border: 1px solid #b6d4da;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Animation for filter collapse */
        #dateFilterCollapse {
            transition: all 0.4s ease;
        }

        /* Quick select styling */
        #quickDateSelect option {
            padding: 8px;
        }

        /* Badge Dark Mode Support */
        body.dark-only .badge.bg-success {
            background-color: #198754 !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-primary {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000000 !important;
        }

        body.dark-only .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }

        body.dark-only .badge.bg-secondary {
            background-color: #6c757d !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-light {
            background-color: #495057 !important;
            color: #ffffff !important;
        }

        /* Export Button Styling */
        #exportExcelBtn {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(25, 135, 84, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
        }

        #exportExcelBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
        }

        #exportExcelBtn i {
            font-size: 1.1em;
        }

        /* Clickable Card Styles */
        .clickable-card {
            transition: all 0.3s ease;
        }

        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .clickable-card.active-filter {
            border: 3px solid #3D9FD8;
            box-shadow: 0 8px 20px rgba(61, 159, 216, 0.4);
            transform: translateY(-3px);
        }

        .clickable-card.active-filter .widget-content {
            position: relative;
        }

        .clickable-card.active-filter::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #3D9FD8;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* Total Sales Display Styling */
        .total-sales-display {
            background-color: #f8f9fa;
            border-left: 4px solid #198754;
            padding: 0.75rem 1rem;
        }

        .total-sales-display .total-sales-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .total-sales-display .total-sales-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #198754;
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>For Your Action</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-reports') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">For Your Action</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <!-- Card 1: Expired Previous Year -->
            <div class="col-md-3">
                <div class="card widget-1 clickable-card" data-card-filter="expired_previous_year" style="cursor: pointer;"
                    title="Click to filter">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round success">
                                <div class="bg-round"><svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#c-customer') }}">
                                        </use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg></div>
                            </div>
                            <div>
                                <h4><span class="counter"
                                        data-target="{{ $expiredPreviousYear }}">{{ $expiredPreviousYear }}</span>
                                </h4><span class="f-light">Expired Previous Year</span>
                            </div>
                        </div>
                        <div class="font-success f-w-500">
                            <i class="bookmark-search me-1" data-feather="calendar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Expired Current Year -->
            <div class="col-md-3">
                <div class="card widget-1 clickable-card" data-card-filter="expired_current_year" style="cursor: pointer;"
                    title="Click to filter">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round warning">
                                <div class="bg-round"><svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#c-profit') }}"> </use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg></div>
                            </div>
                            <div>
                                <h4><span class="counter"
                                        data-target="{{ $expiredCurrentYear }}">{{ $expiredCurrentYear }}</span></h4>
                                <span class="f-light">Expiring Current Year</span>
                            </div>
                        </div>
                        <div class="font-warning f-w-500"><i class="bookmark-search me-1" data-feather="alert-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pending Payment -->
            <div class="col-md-3">
                <div class="card widget-1 clickable-card" data-card-filter="pending_payment" style="cursor: pointer;"
                    title="Click to filter">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round secondary">
                                <div class="bg-round"><svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#c-revenue') }}"> </use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg></div>
                            </div>
                            <div>
                                <h4><span class="counter" data-target="{{ $pendingPayment }}">{{ $pendingPayment }}</span>
                                </h4>
                                <span class="f-light">Pending Payment</span>
                            </div>
                        </div>
                        <div class="font-success f-w-500">
                            <i class="bookmark-search me-1" data-feather="trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Sent to Underwriting -->
            <div class="col-md-3">
                <div class="card widget-1 clickable-card" data-card-filter="sent_uw" style="cursor: pointer;"
                    title="Click to filter">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round primary">
                                <div class="bg-round"><svg class="fill-primary">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#c-invoice') }}">
                                        </use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}">
                                        </use>
                                    </svg></div>
                            </div>
                            <div>
                                <h4><span class="counter"
                                        data-target="{{ $sentToUnderwriting }}">{{ $sentToUnderwriting }}</span></h4><span
                                    class="f-light">Sent to Underwriting</span>
                            </div>
                        </div>
                        <div class="font-danger f-w-500"><i class="bookmark-search me-1" data-feather="trending-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Card -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div>
                            <h5><i class="fa fa-filter me-2"></i>Filter Options</h5>
                            <p class="f-m-light mt-1">Select date range to filter policies</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="policyTypeFilter" class="form-label"><i
                                        class="fa fa-briefcase-medical me-1"></i>Type of Professional Indemnity</label>
                                <select class="form-select" id="policyTypeFilter">
                                    <option value="">All Types</option>
                                    <option value="medical_practice">Medical Practitioner</option>
                                    <option value="dental_practice">Dental Practitioner</option>
                                    <option value="pharmacist">Pharmacist</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label"><i
                                        class="fa fa-info-circle me-1"></i>Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="new_case">New Case</option>
                                    {{-- <option value="new_renewal">New Renewal</option> --}}
                                    <option value="not_paid">Not Paid</option>
                                    <option value="paid">Paid</option>
                                    <option value="sent_uw">Sent UW</option>
                                    <option value="active">Active</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="agentFilter" class="form-label"><i
                                        class="fa fa-user-tie me-1"></i>Agent</label>
                                <select class="form-select" id="agentFilter">
                                    <option value="">All Agents</option>
                                    @foreach (\App\Models\User::role('Agent')->where('approval_status', 'approved')->orderBy('name')->get() as $agentOption)
                                        <option value="{{ $agentOption->id }}">{{ $agentOption->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="expiryYearFilter" class="form-label"><i
                                        class="fa fa-calendar-check me-1"></i>Expiry Year</label>
                                <select class="form-select" id="expiryYearFilter">
                                    <option value="">All Years</option>
                                    @for ($year = date('Y') - 5; $year <= date('Y') + 2; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="dateRangeSelect" class="form-label"><i class="fa fa-calendar me-1"></i>Date
                                    Range</label>
                                <select class="form-select" id="dateRangeSelect">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last7days">Last 7 Days</option>
                                    <option value="last30days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="normalApplyBtn">
                            <div class="col-md-3 offset-md-4">
                                <button type="button" class="btn btn-primary w-100" id="applyFiltersBtn">
                                    <i class="fa fa-filter me-1"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                        <div id="customDateRow" style="display:none;">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" placeholder="Start Date">
                                </div>
                                <div class="col-md-3">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate" placeholder="End Date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary w-100" id="applyCustomFiltersBtn">
                                        <i class="fa fa-filter me-1"></i>Apply Filters
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFiltersBtn">
                                        <i class="fa fa-times me-1"></i>Clear All Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Sales Display -->
            <div class="col-md-12">
                <div class="total-sales-display d-flex justify-content-between align-items-center">
                    <div class="total-sales-label">
                        Total Sales (Filtered):
                    </div>
                    <div class="total-sales-amount" id="totalSalesAmount">
                        RM 0.00
                    </div>
                </div>
            </div>
            <!-- Results Card -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Policies</h5>
                                <p class="f-m-light mt-1 mb-0">Policy status update</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success" id="exportExcelBtn">
                                    <i class="fa fa-file-excel me-2"></i>Export to Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Policy ID</th>
                                        <th>Last Updated</th>
                                        {{-- <th>Type</th> --}}
                                        <th>Status</th>
                                        <th>Expiry Date</th>
                                        <th>Name / Email / Phone</th>
                                        <th width="10%">Class</th>
                                        <th>Amount</th>
                                        <th>Agent</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- DataTables will populate this via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->

    <!-- Policy History Modal -->
    <div class="modal fade" id="policyHistoryModal" tabindex="-1" aria-labelledby="policyHistoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="policyHistoryModalLabel">
                        <i class="fa fa-history me-2"></i>Policy Application History
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="policyHistoryContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Loading policy history...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script>
        let dataTable;
        let activeCardFilter = null; // Track active card filter

        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            dataTable = $(".datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('for-your-action') }}",
                    data: function(d) {
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                        d.policy_type = $('#policyTypeFilter').val();
                        d.status = $('#statusFilter').val();
                        d.agent_id = $('#agentFilter').val();
                        d.expiry_year = $('#expiryYearFilter').val();
                        d.card_filter = activeCardFilter; // Add card filter
                    }
                },
                columns: [{
                        data: 'policy_id',
                        name: 'policy_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'date_changed',
                        name: 'date_changed',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'class',
                        name: 'class',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'agent',
                        name: 'agent',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ], // Order by date changed descending (latest first)
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                responsive: true,
                autoWidth: false,
                drawCallback: function(settings) {
                    // Update total sales when table is redrawn
                    const json = settings.json;
                    if (json && json.totalSales !== undefined) {
                        updateTotalSales(json.totalSales);
                    }
                }
            });

            // Handle clickable card clicks
            $('.clickable-card').on('click', function() {
                const cardFilter = $(this).data('card-filter');

                // Toggle active state
                if (activeCardFilter === cardFilter) {
                    // Clicking same card again - clear filter
                    activeCardFilter = null;
                    $('.clickable-card').removeClass('active-filter');
                } else {
                    // Apply new filter
                    activeCardFilter = cardFilter;
                    $('.clickable-card').removeClass('active-filter');
                    $(this).addClass('active-filter');
                }

                // Reload DataTable with new filter
                dataTable.draw();
            });

            // Date range select change
            $('#dateRangeSelect').on('change', function() {
                const value = $(this).val();
                const today = new Date();
                let startDate = '';
                let endDate = '';

                if (value === 'custom') {
                    // Show custom date row and hide normal apply button
                    $('#customDateRow').show();
                    $('#normalApplyBtn').hide();
                    $('#startDate').val('');
                    $('#endDate').val('');
                } else {
                    // Hide custom date row and show normal apply button
                    $('#customDateRow').hide();
                    $('#normalApplyBtn').show();

                    // Calculate date ranges
                    if (value === 'today') {
                        startDate = endDate = today.toISOString().split('T')[0];
                    } else if (value === 'yesterday') {
                        const yesterday = new Date(today);
                        yesterday.setDate(yesterday.getDate() - 1);
                        startDate = endDate = yesterday.toISOString().split('T')[0];
                    } else if (value === 'last7days') {
                        const last7 = new Date(today);
                        last7.setDate(last7.getDate() - 7);
                        startDate = last7.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                    } else if (value === 'last30days') {
                        const last30 = new Date(today);
                        last30.setDate(last30.getDate() - 30);
                        startDate = last30.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                    }

                    // Set dates
                    $('#startDate').val(startDate);
                    $('#endDate').val(endDate);
                }
            });

            // Apply filters button (for non-custom ranges)
            $('#applyFiltersBtn').on('click', function() {
                dataTable.draw();
            });

            // Apply filters button (for custom date range)
            $('#applyCustomFiltersBtn').on('click', function() {
                dataTable.draw();
            });

            // Clear all filters
            $('#clearFiltersBtn').on('click', function() {
                $('#policyTypeFilter').val('');
                $('#statusFilter').val('');
                $('#agentFilter').val('');
                $('#expiryYearFilter').val('');
                $('#dateRangeSelect').val('');
                $('#startDate').val('');
                $('#endDate').val('');
                $('#customDateRow').hide();
                $('#normalApplyBtn').show();
                dataTable.draw();
            });

            // Export to Excel
            $('#exportExcelBtn').on('click', function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const policyType = $('#policyTypeFilter').val();
                const status = $('#statusFilter').val();
                const agentId = $('#agentFilter').val();
                const expiryYear = $('#expiryYearFilter').val();

                // Build URL with parameters
                let url = "{{ route('for-your-action.export') }}";
                const params = [];

                if (startDate) {
                    params.push('start_date=' + encodeURIComponent(startDate));
                }
                if (endDate) {
                    params.push('end_date=' + encodeURIComponent(endDate));
                }
                if (policyType) {
                    params.push('policy_type=' + encodeURIComponent(policyType));
                }
                if (status) {
                    params.push('status=' + encodeURIComponent(status));
                }
                if (agentId) {
                    params.push('agent_id=' + encodeURIComponent(agentId));
                }
                if (expiryYear) {
                    params.push('expiry_year=' + encodeURIComponent(expiryYear));
                }
                if (activeCardFilter) {
                    params.push('card_filter=' + encodeURIComponent(activeCardFilter));
                }

                if (params.length > 0) {
                    url += '?' + params.join('&');
                }

                // Open in new window to trigger download
                window.location.href = url;
            });
        });

        // Show Policy History Modal
        function showPolicyHistory(userId) {
            if (!userId || userId === 0) {
                alert('Invalid user ID');
                return;
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('policyHistoryModal'));
            modal.show();

            // Reset content to loading state
            document.getElementById('policyHistoryContent').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading policy history...</p>
                </div>
            `;

            // Fetch policy history
            fetch(`/policy-holders/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load policy history');
                    }
                    return response.text();
                })
                .then(html => {
                    // Parse the HTML to extract user info and policy table
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract user information
                    const userCard = doc.querySelector('.info-card');
                    let userName = 'N/A';
                    let userEmail = 'N/A';
                    let userPhone = 'N/A';

                    if (userCard) {
                        const infoValues = userCard.querySelectorAll('.info-value');
                        if (infoValues.length > 0) userName = infoValues[0]?.textContent.trim() || 'N/A';
                        if (infoValues.length > 1) userEmail = infoValues[1]?.textContent.trim() || 'N/A';
                        if (infoValues.length > 2) userPhone = infoValues[2]?.textContent.trim() || 'N/A';
                    }

                    const policyTable = doc.querySelector('.datatable');

                    if (policyTable) {
                        document.getElementById('policyHistoryContent').innerHTML = `
                            <div class="alert alert-light border-start border-primary border-4 mb-3" style="background-color: #f8f9fa;">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="mb-2">
                                            <i class="fa fa-user text-primary me-2"></i>
                                            <strong>Name:</strong> ${userName}
                                        </div>
                                        <div class="mb-2">
                                            <i class="fa fa-envelope text-primary me-2"></i>
                                            <strong>Email:</strong> ${userEmail}
                                        </div>
                                        <div class="mb-0">
                                            <i class="fa fa-phone text-primary me-2"></i>
                                            <strong>Phone:</strong> ${userPhone}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                ${policyTable.outerHTML}
                            </div>
                        `;

                        // Reinitialize DataTable for the modal table
                        $(document.getElementById('policyHistoryContent')).find('.datatable').DataTable({
                            order: [], // Sort by Submitted Date column (index 3) descending
                            pageLength: 5,
                            lengthMenu: [[5, 10, 25], [5, 10, 25]],
                            columnDefs: [
                                { orderable: false, targets: 4 } // Disable sorting on Action column
                            ]
                        });
                    } else {
                        document.getElementById('policyHistoryContent').innerHTML = `
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                No policy applications found for this user.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('policyHistoryContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            Failed to load policy history. Please try again.
                        </div>
                    `;
                });
        }

        // Helper function to update total sales display
        function updateTotalSales(amount) {
            const formattedAmount = 'RM ' + parseFloat(amount || 0).toLocaleString('en-MY', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            $('#totalSalesAmount').text(formattedAmount);
        }
    </script>
@endsection
