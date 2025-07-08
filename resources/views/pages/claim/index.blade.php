@extends('layouts.main')

@section('title', 'For Your Action')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Claims</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-email') }}"></use>
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
                                <h5>Claims</h5>
                                <p class="f-m-light mt-1">Claims and Notifications History</p>
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
        $(document).ready(function () {
            $(".datatable").DataTable();
        });
    </script>
@endsection
