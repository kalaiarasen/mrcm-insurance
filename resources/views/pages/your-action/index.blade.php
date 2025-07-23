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
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        #dateFilterCollapse .form-control, 
        #dateFilterCollapse .form-select {
            border: 2px solid #e3e6ea;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Animation for filter collapse */
        #dateFilterCollapse {
            transition: all 0.4s ease;
        }
        
        /* Quick select styling */
        #quickDateSelect option {
            padding: 8px;
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
            <div class="col-md-3">
                <div class="card widget-1">
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
                                <h4>$<span class="counter" data-target="45195">0</span></h4><span
                                    class="f-light">Revenue</span>
                            </div>
                        </div>
                        <div class="font-success f-w-500"><i class="bookmark-search me-1"
                                data-feather="trending-up"></i><span class="txt-success">+50%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round success">
                                <div class="bg-round"><svg>
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#c-customer') }}">
                                        </use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}">
                                        </use>
                                    </svg></div>
                            </div>
                            <div>
                                <h4> <span class="counter" data-target="845">0</span>+</h4><span
                                    class="f-light">Customers</span>
                            </div>
                        </div>
                        <div class="font-danger f-w-500"><i class="bookmark-search me-1"
                                data-feather="trending-down"></i><span class="txt-danger">-40%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card widget-1">
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
                                <h4> <span class="counter" data-target="80">0</span>%</h4><span
                                    class="f-light">Profit</span>
                            </div>
                        </div>
                        <div class="font-danger f-w-500"><i class="bookmark-search me-1"
                                data-feather="trending-down"></i><span class="txt-danger">-20%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card widget-1">
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
                                <h4 class="counter" data-target="10905">0</h4><span
                                    class="f-light">Invoices</span>
                            </div>
                        </div>
                        <div class="font-success f-w-500"><i class="bookmark-search me-1"
                                data-feather="trending-up"></i><span class="txt-success">+50%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Policies</h5>
                                <p class="f-m-light mt-1">Policy status update</p>
                            </div>
                            <div class="header-actions">
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#dateFilterCollapse" aria-expanded="false" aria-controls="dateFilterCollapse">
                                    <i class="fa fa-filter me-1"></i>Date Filter
                                </button>
                            </div>
                        </div>
                        
                        <!-- Date Filter Section -->
                        <div class="collapse mt-3" id="dateFilterCollapse">
                            <div class="card card-body border-light bg-light">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label for="startDate" class="form-label fw-bold text-dark">
                                            <i class="fa fa-calendar-alt text-primary me-1"></i>Start Date
                                        </label>
                                        <input type="date" class="form-control" id="startDate" name="start_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="endDate" class="form-label fw-bold text-dark">
                                            <i class="fa fa-calendar-alt text-primary me-1"></i>End Date
                                        </label>
                                        <input type="date" class="form-control" id="endDate" name="end_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-dark">Quick Select</label>
                                        <select class="form-select" id="quickDateSelect">
                                            <option value="">Select Range</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last7days">Last 7 Days</option>
                                            <option value="last30days">Last 30 Days</option>
                                            <option value="thismonth">This Month</option>
                                            <option value="lastmonth">Last Month</option>
                                            <option value="thisyear">This Year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success flex-fill" onclick="applyDateFilter()">
                                                <i class="fa fa-search me-1"></i>Apply Filter
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="clearDateFilter()" title="Clear Filter">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Filter Display -->
                                <div id="activeFilterDisplay" class="mt-3" style="display: none;">
                                    <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                        <i class="fa fa-info-circle me-1"></i>
                                        <strong>Active Filter:</strong> <span id="filterText"></span>
                                        <button type="button" class="btn-close" aria-label="Close" onclick="clearDateFilter()"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-striped border datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>25/04/2011</td>
                                        <td>$320,800</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>25/07/2015</td>
                                        <td>$170,750</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ashton Cox</td>
                                        <td>Junior Technical Author</td>
                                        <td>San Francisco</td>
                                        <td>66</td>
                                        <td>12/01/2009</td>
                                        <td>$86,000</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Cedric Kelly</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>29/03/2016</td>
                                        <td>$433,060</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Airi Satou</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>33</td>
                                        <td>28/11/2008</td>
                                        <td>$162,700</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Brielle Williamson</td>
                                        <td>Integration Specialist</td>
                                        <td>New York</td>
                                        <td>61</td>
                                        <td>02/12/2012</td>
                                        <td>$372,000</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Herrod Chandler</td>
                                        <td>Sales Assistant</td>
                                        <td>San Francisco</td>
                                        <td>59</td>
                                        <td>06/08/2012</td>
                                        <td>$137,500</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rhona Davidson</td>
                                        <td>Integration Specialist</td>
                                        <td>Tokyo</td>
                                        <td>55</td>
                                        <td>14/10/2010</td>
                                        <td>$327,900</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Colleen Hurst</td>
                                        <td>Javascript Developer</td>
                                        <td>San Francisco</td>
                                        <td>39</td>
                                        <td>15/09/2009</td>
                                        <td>$205,500</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                        <td>23</td>
                                        <td>13/12/2008</td>
                                        <td>$103,600</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jena Gaines</td>
                                        <td>Office Manager</td>
                                        <td>London</td>
                                        <td>30</td>
                                        <td>19/12/2008</td>
                                        <td>$90,560</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quinn Flynn</td>
                                        <td>Support Lead</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>03/03/2013</td>
                                        <td>$342,000</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="#!"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                </li>
                                                <li class="delete"><a href="#!"><i
                                                            class="fa-solid fa-trash-can"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
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
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        let dataTable;
        
        $(document).ready(function () {
            // Initialize DataTable
            dataTable = $(".datatable").DataTable({
                "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                "responsive": true,
                "pageLength": 10,
                "order": [[ 4, "desc" ]], // Sort by date column by default
            });
            
            // Set default end date to today
            document.getElementById('endDate').value = new Date().toISOString().split('T')[0];
        });

        // Quick Date Select Handler
        document.getElementById('quickDateSelect').addEventListener('change', function() {
            const selection = this.value;
            const today = new Date();
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            
            let startDate, endDate;
            
            switch(selection) {
                case 'today':
                    startDate = endDate = today;
                    break;
                case 'yesterday':
                    startDate = endDate = new Date(today.getTime() - 24 * 60 * 60 * 1000);
                    break;
                case 'last7days':
                    startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                case 'last30days':
                    startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                case 'thismonth':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = today;
                    break;
                case 'lastmonth':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case 'thisyear':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = today;
                    break;
                default:
                    startDateInput.value = '';
                    endDateInput.value = '';
                    return;
            }
            
            if (startDate) {
                startDateInput.value = startDate.toISOString().split('T')[0];
            }
            if (endDate) {
                endDateInput.value = endDate.toISOString().split('T')[0];
            }
        });

        // Apply Date Filter Function
        function applyDateFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const quickSelect = document.getElementById('quickDateSelect').value;
            
            // Validation
            if (!startDate && !endDate && !quickSelect) {
                showNotification('Please select a date range or use quick select.', 'warning');
                return;
            }
            
            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                showNotification('Start date cannot be later than end date.', 'error');
                return;
            }
            
            // Show active filter
            displayActiveFilter(startDate, endDate, quickSelect);
            
            // Apply filter to DataTable (example - you'll need to implement actual filtering based on your data)
            filterDataTable(startDate, endDate);
            
            // Show success notification
            showNotification('Date filter applied successfully!', 'success');
            
            // Optionally close the filter panel
            // document.querySelector('[data-bs-target="#dateFilterCollapse"]').click();
        }

        // Clear Date Filter Function
        function clearDateFilter() {
            // Clear inputs
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('quickDateSelect').value = '';
            
            // Hide active filter display
            document.getElementById('activeFilterDisplay').style.display = 'none';
            
            // Clear DataTable filter
            if (dataTable) {
                dataTable.search('').draw();
            }
            
            showNotification('Date filter cleared.', 'info');
        }

        // Display Active Filter
        function displayActiveFilter(startDate, endDate, quickSelect) {
            const filterDisplay = document.getElementById('activeFilterDisplay');
            const filterText = document.getElementById('filterText');
            
            let text = '';
            
            if (quickSelect) {
                const options = {
                    'today': 'Today',
                    'yesterday': 'Yesterday', 
                    'last7days': 'Last 7 Days',
                    'last30days': 'Last 30 Days',
                    'thismonth': 'This Month',
                    'lastmonth': 'Last Month',
                    'thisyear': 'This Year'
                };
                text = options[quickSelect] || quickSelect;
            } else {
                if (startDate && endDate) {
                    text = `${formatDate(startDate)} to ${formatDate(endDate)}`;
                } else if (startDate) {
                    text = `From ${formatDate(startDate)}`;
                } else if (endDate) {
                    text = `Until ${formatDate(endDate)}`;
                }
            }
            
            filterText.textContent = text;
            filterDisplay.style.display = 'block';
        }

        // Format Date for Display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Filter DataTable (implement based on your data structure)
        function filterDataTable(startDate, endDate) {
            // Example: Custom search function for DataTable
            // You'll need to adapt this based on your actual date column format
            
            if (!startDate && !endDate) {
                dataTable.search('').draw();
                return;
            }
            
            // Example implementation - you may need to customize this
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const dateColumn = 4; // Assuming date is in column 4 (Start date)
                    const rowDate = new Date(data[dateColumn]);
                    
                    if (startDate && endDate) {
                        return rowDate >= new Date(startDate) && rowDate <= new Date(endDate);
                    } else if (startDate) {
                        return rowDate >= new Date(startDate);
                    } else if (endDate) {
                        return rowDate <= new Date(endDate);
                    }
                    
                    return true;
                }
            );
            
            dataTable.draw();
        }

        // Show Notification
        function showNotification(message, type = 'info') {
            // You can implement this using your preferred notification library
            // For now, just using alert - replace with toast notifications
            const types = {
                'success': '✅',
                'error': '❌', 
                'warning': '⚠️',
                'info': 'ℹ️'
            };
            
            // Create a temporary notification
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${types[type]} ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Clear custom search when clearing filter
        function clearCustomSearch() {
            $.fn.dataTable.ext.search = [];
        }
    </script>
@endsection
