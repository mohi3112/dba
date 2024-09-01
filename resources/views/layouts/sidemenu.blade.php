<?php
$currentRole = getUserRoles();
?>

<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <!-- <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                        <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                        <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                        <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                    <mask id="mask-2" fill="white">
                                        <use xlink:href="#path-1"></use>
                                    </mask>
                                    <use fill="#696cff" xlink:href="#path-1"></use>
                                    <g id="Path-3" mask="url(#mask-2)">
                                        <use fill="#696cff" xlink:href="#path-3"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                    </g>
                                    <g id="Path-4" mask="url(#mask-2)">
                                        <use fill="#696cff" xlink:href="#path-4"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                    </g>
                                </g>
                                <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                    <use fill="#696cff" xlink:href="#path-5"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg> -->
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <!-- Layouts -->

        <!-- Start account settings -->
        <li class="menu-item {{ request()->is('account*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="account">Account Settings</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('account') ? 'active' : '' }}">
                    <a href="{{route('account')}}" class="menu-link">
                        <div data-i18n="account">Account</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End account settings -->
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['manager'])
        <!-- Start employee -->
        <li class="menu-item {{ request()->is('employee*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="account">Employees</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('employees') ? 'active' : '' }}">
                    <a href="{{route('employees')}}" class="menu-link">
                        <div data-i18n="update-requests">All Employees</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('employee/add') ? 'active' : '' }}">
                    <a href="{{route('employee.add')}}" class="menu-link">
                        <div data-i18n="Add role">Add Employee</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('employee/daily-attendance') ? 'active' : '' }}">
                    <a href="{{route('employees.daily-attendance')}}" class="menu-link">
                        <div data-i18n="Add role">Daily Attendance</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('employee/attendance-report') ? 'active' : '' }}">
                    <a href="{{route('employees.attendance-report')}}" class="menu-link">
                        <div data-i18n="Add role">Attendance Report</div>
                    </a>
                </li>
                <!-- <li class="menu-item {{ request()->is('employees') ? 'active' : '' }}">
                    <a href="{{route('employees')}}" class="menu-link">
                        <div data-i18n="Add role">Attendance Report</div>
                    </a>
                </li> -->
            </ul>
        </li>
        <!-- End employee -->
        <!-- Start Loan -->
        <li class="menu-item {{ request()->is('loan*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="loans">Loan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('loans') ? 'active' : '' }}">
                    <a href="{{route('loans')}}" class="menu-link">
                        <div data-i18n="loans">All Loan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('loans/add') ? 'active' : '' }}">
                    <a href="{{route('loans.add')}}" class="menu-link">
                        <div data-i18n="Add loans">Add Loan</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Loan -->
        @endif

        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'])
        <!-- Start requests -->
        <li class="menu-item {{ request()->is('user*') || request()->is('request*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="account">Requests</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('users/update-requests') ? 'active' : '' }}">
                    <a href="{{route('users.update-requests')}}" class="menu-link">
                        <div data-i18n="update-requests">User Requests</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('requests/all-requests') ? 'active' : '' }}">
                    <a href="{{route('requests.update-requests')}}" class="menu-link">
                        <div data-i18n="update-requests">Other Requests</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End requests -->
        @endif

        <!-- Start roles -->
        @if(auth()->user()->hasRole('superadmin'))
        <li class="menu-item {{ request()->is('role*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="roles">Roles</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('roles') ? 'active' : '' }}">
                    <a href="{{route('roles')}}" class="menu-link">
                        <div data-i18n="roles">All Roles</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('roles/add') ? 'active' : '' }}">
                    <a href="{{route('roles.add')}}" class="menu-link">
                        <div data-i18n="Add role">Add Role</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        <!-- End roles -->
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'])
        <!-- Start locations -->
        <li class="menu-item {{ request()->is('location*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="locations">Locations</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('locations') ? 'active' : '' }}">
                    <a href="{{route('locations')}}" class="menu-link">
                        <div data-i18n="locations">All Locations</div>
                    </a>
                </li>
                @if(!auth()->user()->hasRole('vendor'))
                <li class="menu-item {{ request()->is('locations/add') ? 'active' : '' }}">
                    <a href="{{route('locations.add')}}" class="menu-link">
                        <div data-i18n="Add location">Add Location</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        <!-- End locations -->
        @endif

        <!-- Start Lawyers -->
        @if(!$currentRole['vendor'])
        <li class="menu-item {{ request()->is('lawyer*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Lawyers">Lawyers</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('lawyers') ? 'active' : '' }}">
                    <a href="{{route('users')}}" class="menu-link">
                        <div data-i18n="Summary">Summary</div>
                    </a>
                </li>
                @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['manager'] || $currentRole['joint_secretary'] || $currentRole['executive_member'])
                <li class="menu-item {{ request()->is('lawyers/add') ? 'active' : '' }}">
                    <a href="{{route('users.add')}}" class="menu-link">
                        <div data-i18n="Add Lawyer">Add Lawyer</div>
                    </a>
                </li>
                @endif
                <li class="menu-item {{ request()->is('lawyers/telephone-directory') ? 'active' : '' }}">
                    <a href="{{route('users.telephone-directory')}}" class="menu-link">
                        <div data-i18n="telephone-directory">Telephone Directory</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('lawyers/voting-list') ? 'active' : '' }}">
                    <a href="{{route('users.voting-list')}}" class="menu-link">
                        <div data-i18n="voting-list">Voting List</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        <!-- End Lawyers -->
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['vendor'])
        <!-- Start vendors -->
        <li class="menu-item {{ request()->is('vendor*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="vendors">Vendors</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('vendors') ? 'active' : '' }}">
                    <a href="{{route('vendors')}}" class="menu-link">
                        <div data-i18n="vendors">All Vendors</div>
                    </a>
                </li>
                @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'])
                <li class="menu-item {{ request()->is('vendors/add') ? 'active' : '' }}">
                    <a href="{{route('users.add')}}?type=vendor" class="menu-link">
                        <div data-i18n="Add vendor">Add Vendor</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        <!-- End vendors -->
        @endif

        <!-- Start Books -->
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['librarian'])
        <li class="menu-item {{ request()->is('bookCategor*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="bookCategories">Books Category</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('bookCategories') ? 'active' : '' }}">
                    <a href="{{route('bookCategories')}}" class="menu-link">
                        <div data-i18n="bookCategories">All Books Categories</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('bookCategories/add') ? 'active' : '' }}">
                    <a href="{{route('bookCategories.add')}}" class="menu-link">
                        <div data-i18n="Add bookCategories">Add Books Category</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Books -->

        <!-- Start Books -->
        <li class="menu-item {{ request()->is('books*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="books">Books</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('books') ? 'active' : '' }}">
                    <a href="{{route('books')}}" class="menu-link">
                        <div data-i18n="books">All Books</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('books/add') ? 'active' : '' }}">
                    <a href="{{route('books.add')}}" class="menu-link">
                        <div data-i18n="Add books">Add Book</div>
                    </a>
                </li>
                <!-- <li class="menu-item {{ request()->is('books/issued-books') ? 'active' : '' }}">
                    <a href="{{route('books.issued-books')}}" class="menu-link">
                        <div data-i18n="Issued books">Issued Books</div>
                    </a>
                </li> -->
            </ul>
        </li>
        @endif
        <!-- End Books -->
        @if($currentRole['president'] || $currentRole['finance_secretary'])
        <!-- Start Payments -->
        <li class="menu-item {{ request()->is('payment*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="payments">Payments</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('payments') ? 'active' : '' }}">
                    <a href="{{route('payments')}}" class="menu-link">
                        <div data-i18n="payments">All Payments</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('payments/add') ? 'active' : '' }}">
                    <a href="{{route('payments.add')}}" class="menu-link">
                        <div data-i18n="Add payments">Add Payments</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Payments -->
        @endif
        <!-- Start Subscriptions -->
        @if(!$currentRole['vendor'])
        <li class="menu-item {{ request()->is('subscription*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="subscriptions">Subscriptions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('subscriptions') ? 'active' : '' }}">
                    <a href="{{route('subscriptions')}}" class="menu-link">
                        <div data-i18n="subscriptions">All Subscriptions</div>
                    </a>
                </li>
                @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['manager'])
                <li class="menu-item {{ request()->is('subscriptions/upcoming-subscription') ? 'active' : '' }}">
                    <a href="{{route('subscriptions.getUpcomingSubscriptions')}}" class="menu-link">
                        <div data-i18n="upcoming subscriptions">Upcoming Subscriptions</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('subscriptions/add') ? 'active' : '' }}">
                    <a href="{{route('subscriptions.add')}}" class="menu-link">
                        <div data-i18n="Add subscriptions">Add Subscriptions</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        <!-- End Subscriptions -->
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'])
        <!-- Start Voucher -->
        <li class="menu-item {{ request()->is('voucher*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="vouchers">Voucher</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('vouchers') ? 'active' : '' }}">
                    <a href="{{route('vouchers')}}" class="menu-link">
                        <div data-i18n="vouchers">All Voucher</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('vouchers/add') ? 'active' : '' }}">
                    <a href="{{route('vouchers.add')}}" class="menu-link">
                        <div data-i18n="Add vouchers">Add Voucher</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Voucher -->
        @endif
        @if($currentRole['president'] || $currentRole['finance_secretary'])
        <!-- Start Vakalatnama -->
        <li class="menu-item {{ request()->is('vakalatnama*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="vakalatnamas">Vakalatnama</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('vakalatnama/summary') ? 'active' : '' }}">
                    <a href="{{route('vakalatnamas')}}" class="menu-link">
                        <div data-i18n="vakalatnamas">All Vakalatnama</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('vakalatnama/vakalatnama-form') ? 'active' : '' }}">
                    <a href="{{route('vakalatnama.form')}}" class="menu-link">
                        <div data-i18n="Add vakalatnamas">Add Vakalatnama</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Voucher -->
        @endif
        @if($currentRole['president'] || $currentRole['vice_president'] || $currentRole['finance_secretary'] || $currentRole['secretary'] || $currentRole['manager'])
        <!-- Start Rent -->
        <li class="menu-item {{ request()->is('rent*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="rents">Rent</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('rents') ? 'active' : '' }}">
                    <a href="{{route('rents')}}" class="menu-link">
                        <div data-i18n="rents">All Rent</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('rents/add') ? 'active' : '' }}">
                    <a href="{{route('rents.add')}}" class="menu-link">
                        <div data-i18n="Add rents">Add Rent</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('rent/pending-rents') ? 'active' : '' }}">
                    <a href="{{route('rents.pending-rents')}}" class="menu-link">
                        <div data-i18n="Pending rents">Pending Rents</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Rent -->
        @endif
    </ul>
</aside>
<!-- / Menu -->