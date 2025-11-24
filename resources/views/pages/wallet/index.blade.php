@extends('layouts.main')

@section('title', 'Wallet Management')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Wallet Management</h3>
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
                        <li class="breadcrumb-item active">Wallet Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Success/Error Messages -->
        <div id="alert-container"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>User Wallets</h5>
                                <p class="f-m-light mt-1">Manage user wallet amounts</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Date Created</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Nation Status</th>
                                        <th>NRIC No</th>
                                        <th>Email</th>
                                        <th>Contact No</th>
                                        <th>Wallet Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr id="user-row-{{ $user->id }}">
                                            <td>
                                                <small>{{ $user->created_at->format('d-M-Y') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $user->applicantProfile?->title ?? '' }}{{ $user->applicantProfile?->title ? '. ' : '' }}{{ $user->name }}</strong>
                                            </td>
                                            <td>
                                                {{ ucwords($user->applicantProfile?->gender ?? '-') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucwords($user->applicantProfile?->nationality_status ?? '-') }}</span>
                                            </td>
                                            <td>
                                                {{ $user->applicantProfile?->nric_number ?? $user->applicantProfile?->passport_number ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                {{ $user->contact_no }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary wallet-amount" id="wallet-{{ $user->id }}" data-amount="{{ floatval($user->wallet_amount) }}">
                                                    RM {{ number_format($user->wallet_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success me-1" onclick="openAddModal({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Add Amount">
                                                    <i class="fa fa-plus"></i> Add
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="openDeductModal({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Deduct Amount">
                                                    <i class="fa fa-minus"></i> Deduct
                                                </button>
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
    </div>

    <!-- Add Amount Modal -->
    <div class="modal fade" id="addAmountModal" tabindex="-1" aria-labelledby="addAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addAmountModalLabel">
                        <i class="fa fa-plus-circle me-2"></i>Add Amount to Wallet
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addAmountForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="add_user_id" name="user_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">User</label>
                            <input type="text" class="form-control" id="add_user_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Balance</label>
                            <input type="text" class="form-control" id="add_current_balance" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="add_amount" class="form-label fw-bold">
                                Amount to Add <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" class="form-control" id="add_amount" name="amount" step="0.01" min="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus me-2"></i>Add Amount
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deduct Amount Modal -->
    <div class="modal fade" id="deductAmountModal" tabindex="-1" aria-labelledby="deductAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deductAmountModalLabel">
                        <i class="fa fa-minus-circle me-2"></i>Deduct Amount from Wallet
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deductAmountForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="deduct_user_id" name="user_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">User</label>
                            <input type="text" class="form-control" id="deduct_user_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Balance</label>
                            <input type="text" class="form-control" id="deduct_current_balance" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deduct_amount" class="form-label fw-bold">
                                Amount to Deduct <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" class="form-control" id="deduct_amount" name="amount" step="0.01" min="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-minus me-2"></i>Deduct Amount
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datatable").DataTable({
                order: [[0, 'asc']] // Sort by name ascending
            });
        });

        let addModal, deductModal;

        document.addEventListener('DOMContentLoaded', function() {
            addModal = new bootstrap.Modal(document.getElementById('addAmountModal'));
            deductModal = new bootstrap.Modal(document.getElementById('deductAmountModal'));
        });

        function openAddModal(userId, userName) {
            // Get current balance from data attribute
            const currentBalance = parseFloat(document.getElementById('wallet-' + userId).dataset.amount);
            
            document.getElementById('add_user_id').value = userId;
            document.getElementById('add_user_name').value = userName;
            document.getElementById('add_current_balance').value = 'RM ' + currentBalance.toFixed(2);
            document.getElementById('add_amount').value = '';
            addModal.show();
        }

        function openDeductModal(userId, userName) {
            // Get current balance from data attribute
            const currentBalance = parseFloat(document.getElementById('wallet-' + userId).dataset.amount);
            
            document.getElementById('deduct_user_id').value = userId;
            document.getElementById('deduct_user_name').value = userName;
            document.getElementById('deduct_current_balance').value = 'RM ' + currentBalance.toFixed(2);
            document.getElementById('deduct_amount').value = '';
            deductModal.show();
        }

        // Handle Add Amount Form Submit
        document.getElementById('addAmountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';

            fetch('{{ route("wallet.add-amount") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update wallet amount in table and data attribute
                    const userId = document.getElementById('add_user_id').value;
                    const walletElement = document.getElementById('wallet-' + userId);
                    walletElement.textContent = 'RM ' + data.new_amount;
                    walletElement.setAttribute('data-amount', data.new_amount.replace(/,/g, ''));
                    
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Close modal
                    addModal.hide();
                    
                    // Reset form
                    document.getElementById('addAmountForm').reset();
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'An error occurred. Please try again.');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-plus me-2"></i>Add Amount';
            });
        });

        // Handle Deduct Amount Form Submit
        document.getElementById('deductAmountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';

            fetch('{{ route("wallet.deduct-amount") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update wallet amount in table and data attribute
                    const userId = document.getElementById('deduct_user_id').value;
                    const walletElement = document.getElementById('wallet-' + userId);
                    walletElement.textContent = 'RM ' + data.new_amount;
                    walletElement.setAttribute('data-amount', data.new_amount.replace(/,/g, ''));
                    
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Close modal
                    deductModal.hide();
                    
                    // Reset form
                    document.getElementById('deductAmountForm').reset();
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'An error occurred. Please try again.');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-minus me-2"></i>Deduct Amount';
            });
        });

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        }
    </script>
@endsection
