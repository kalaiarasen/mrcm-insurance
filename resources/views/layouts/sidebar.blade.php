<!-- Page Sidebar Start-->
<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper">
            <a href="{{ route('layout_light') }}"><img class="img-fluid for-light w-75" src="{{ asset('img/logo.png') }}" alt="" /><img class="img-fluid for-dark w-75" src="{{ asset('img/logo.png') }}" alt="" /></a>

            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="{{ route('layout_light') }}"><img class="img-fluid" src="{{ asset('img/favicon.png') }}" alt="" style="width:40px; height:40px;" /></a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    {{-- <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li> --}}
                    @role('Client')
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-social') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                            </svg>
                            <span>Professional Indemnity</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use>
                            </svg>
                            <span>Clinic Cover</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                            </svg>
                            <span>Group/Entity Cover</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-task') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-task') }}"></use>
                            </svg>
                            <span>Allied Healthcare</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-button') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-button') }}"></use>
                            </svg>
                            <span>Q&A</span>
                        </a>
                    </li>
                    @endrole
                    @hasanyrole('Super Admin|Admin|Agent')
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('for-your-action') ? 'active' : '' }}" href="{{ route('for-your-action') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-reports') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-reports') }}"></use>
                            </svg>
                            <span>For your action</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('policy-holder') ? 'active' : '' }}" href="{{ route('policy-holder') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-task') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-task') }}"></use>
                            </svg>
                            <span>Policy Holder</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('claims.*') ? 'active' : '' }}" href="{{ route('claims.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-email') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-email') }}"></use>
                            </svg>
                            <span>Claims Management</span>
                        </a>
                    </li>
                    {{-- <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('new-policy') ? 'active' : '' }}" href="{{ route('new-policy') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use>
                            </svg>
                            <span>New Policy</span>
                        </a>
                    </li> --}}
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-button') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-button') }}"></use>
                            </svg>
                            <span>Announcement</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                            </svg>
                            <span>Users</span>
                        </a>
                    </li>
                    @endhasanyrole
                    @role('Super Admin')
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-social') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                            </svg>
                            <span>Roles & Permissions</span>
                        </a>
                    </li>
                    @endrole
                    @hasanyrole('Super Admin|Admin')
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('wallet.*') ? 'active' : '' }}" href="{{ route('wallet.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-price') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-price') }}"></use>
                            </svg>
                            <span>Wallet Management</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('agents.*') ? 'active' : '' }}" href="{{ route('agents.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                            </svg>
                            <span>Agent Management</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('discounts.*') ? 'active' : '' }}" href="{{ route('discounts.index') }}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-price') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-price') }}"></use>
                            </svg>
                            <span>Discount Setup</span>
                        </a>
                    </li>
                    {{-- <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                            </svg>
                            <span>Client Management</span>
                        </a>
                    </li> --}}
                    @endhasanyrole
                    {{-- <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-starter-kit') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-starter-kit') }}"></use>
                            </svg>
                            <span>Starter kit</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-title" href="#">
                                    color version<span class="sub-arrow"><i class="fa-solid fa-angle-right"></i></span>
                                </a>
                                <ul class="sidebar-submenu submenu-content">
                                    <li><a href="{{ route('layout_light') }}">Layout Light</a></li>
                                    <li><a href="{{ route('layout_dark') }}">Layout Dark</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="submenu-title" href="#">
                                    Page layout<span class="sub-arrow"><i class="fa-solid fa-angle-right"></i></span>
                                </a>
                                <ul class="sidebar-submenu submenu-content">
                                    <li><a href="{{ route('box_layout') }}">Boxed</a></li>
                                    <li><a href="{{ route('rtl_layout') }}">RTL</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('hide_menu_on_scroll') }}"><span>Hide menu on Scroll</span></a>
                            </li>
                            <li>
                                <a class="submenu-title" href="#">
                                    Footers<span class="sub-arrow"><i class="fa-solid fa-angle-right"></i></span>
                                </a>
                                <ul class="sidebar-submenu submenu-content">
                                    <li><a href="{{ route('footer_light') }}">Footer Light</a></li>
                                    <li><a href="{{ route('footer_dark') }}">Footer Dark</a></li>
                                    <li><a href="{{ route('footer_fixed') }}">Footer Fixed</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="https://support.pixelstrap.com/" target="_blank">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-social') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                            </svg>
                            <span>Raise Support</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a class="sidebar-link sidebar-title link-nav" href="https://docs.pixelstrap.com/cuba/laravel/document/" target="_blank">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use>
                            </svg>
                            <span>Documentation </span>
                        </a>
                    </li> --}}
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends-->
