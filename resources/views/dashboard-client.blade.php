@extends('layouts.main')

@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        .announcement-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .announcement-item {
            transition: all 0.3s ease;
        }
        .announcement-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .announcement-title {
            font-weight: 600;
        }
        .announcement-description {
            line-height: 1.5;
        }
        .quick-actions .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .quick-actions .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
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
                        <li class="breadcrumb-item active">Default</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-dashboard">
        <!-- First Row - Announcements and Action Buttons -->
        <div class="row mb-4">
            <!-- Announcements Section -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>📢 Latest Announcements</h5>
                    </div>
                    <div class="card-body">
                        @if($announcements && $announcements->count() > 0)
                            <div class="announcement-list">
                                @foreach($announcements as $announcement)
                                    <div class="announcement-item mb-3 p-3 border rounded">
                                        <h6 class="announcement-title mb-2">{{ $announcement->title }}</h6>
                                        <p class="announcement-description mb-1">{{ $announcement->description }}</p>
                                        <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No announcements available at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>⚡ Quick Actions</h5>
                    </div>
                    <div class="card-body quick-actions">
                        <div class="d-grid gap-2">
                            <a href="{{ route('new-policy') }}" class="btn btn-primary">
                                <i class="fa fa-plus me-2"></i>New Policy Application
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="fa fa-file-text me-2"></i>View My Policies
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fa fa-exclamation-triangle me-2"></i>File a Claim
                            </a>
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="fa fa-user me-2"></i>Update Profile
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                </div>
            </div>
        </div>

        <!-- Second Row - Widget Statistics (Keep existing) -->
        <div class="row widget-grid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>Policies</h5>
                        <p class="f-m-light mt-1">View policies</p>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>Claims Notification</h5>
                        <p class="f-m-light mt-1">Claims and Notifications History</p>
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
    <script src="{{ asset('assets/js/clock.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datatable").DataTable();
        });
    </script>
@endsection
