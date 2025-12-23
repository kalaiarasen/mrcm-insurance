@extends('layouts.main')

@section('title', 'My Commissions')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>My Commissions</h3>
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
                        <li class="breadcrumb-item active">My Commissions</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Earned</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value">RM {{ number_format($totalEarned, 2) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success rounded fs-3">
                                    <i class="bx bx-dollar-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Active Commissions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value">RM {{ number_format($totalActive, 2) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success rounded fs-3">
                                    <i class="bx bx-check-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Commissions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value">RM {{ number_format($totalPending, 2) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning rounded fs-3">
                                    <i class="bx bx-time-five"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Paid Commissions</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value">RM {{ number_format($totalPayments, 2) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info rounded fs-3">
                                    <i class="bx bx-money"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Commission History</h5>
                            <p class="f-m-light mt-1">View your commission earnings</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="viewPaymentHistory()">
                            <i class="fa fa-receipt me-1"></i>View Payment History
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table-striped border datatable" id="commissionsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client Name</th>
                                    <th>Client Code</th>
                                    <th>Policy Reference</th>
                                    <th>Base Amount</th>
                                    <th>Rate</th>
                                    <th>Commission</th>
                                    <th>Status</th>
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
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('agent.commissions') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'date',
                        name: 'created_at',
                        orderable: true
                    },
                    {
                        data: 'client_name',
                        name: 'client_name',
                        orderable: false
                    },
                    {
                        data: 'client_code',
                        name: 'client_code',
                        orderable: false
                    },
                    {
                        data: 'policy_ref',
                        name: 'policy_ref',
                        orderable: false
                    },
                    {
                        data: 'base_amount',
                        name: 'base_amount',
                        orderable: true
                    },
                    {
                        data: 'commission_rate',
                        name: 'commission_rate',
                        orderable: true
                    },
                    {
                        data: 'commission_amount',
                        name: 'commission_amount',
                        orderable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: true
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                responsive: true
            });
        });

        // View payment history
        function viewPaymentHistory() {
            fetch('{{ route('agent.payments') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payments = data.data.payments;
                        const totalPayments = data.data.total_payments;

                        // Update modal content
                        document.getElementById('totalPaymentsReceived').textContent = parseFloat(totalPayments || 0)
                            .toFixed(2);

                        // Populate payment table
                        const tbody = document.getElementById('paymentHistoryTableBody');
                        tbody.innerHTML = '';

                        if (payments.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="6" class="text-center text-muted">No payments received yet</td></tr>';
                        } else {
                            payments.forEach(payment => {
                                const row = `
                                    <tr>
                                        <td>${payment.payment_date}</td>
                                        <td><strong>RM ${parseFloat(payment.amount).toFixed(2)}</strong></td>
                                        <td>${payment.payment_method}</td>
                                        <td>${payment.reference_number || '-'}</td>
                                        <td>${payment.receipt_url ? '<a href="' + payment.receipt_url + '" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i></a>' : '-'}</td>
                                        <td><small class="text-muted">${payment.created_by}</small></td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                        }

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('paymentHistoryModal'));
                        modal.show();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    <!-- Payment History Modal -->
    <div class="modal fade" id="paymentHistoryModal" tabindex="-1" aria-labelledby="paymentHistoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="paymentHistoryModalLabel">
                        <i class="fa fa-receipt me-2"></i>Payment History
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Total Payments Received:</strong> RM <span id="totalPaymentsReceived">0.00</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Receipt</th>
                                    <th>Issued By</th>
                                </tr>
                            </thead>
                            <tbody id="paymentHistoryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
