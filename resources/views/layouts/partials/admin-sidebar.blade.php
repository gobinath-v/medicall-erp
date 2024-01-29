@php
    $previousEvents = getPreviousEvents();
    $eventId = request('eventId');
    $isPreviousEvent = in_array( $eventId, $previousEvents->pluck('id')->toArray());
    $currentRouteName = Route::currentRouteName();
    $previousEventActive = in_array($currentRouteName, ['admin-dashboard', 'exhibitor.summary', 'appointment.summary', 'visitors.summary', 'visitors.edit', 'exhibitor.edit']);
    $masterMenuIsActive = in_array($currentRouteName, ['exhibitor.edit', 'visitors.edit', 'employees.index', 'visitors.summary', 'exhibitor.summary', 'events', 'category', 'products']);
    $eventOverviewIsActive = in_array($currentRouteName, ['exhibitor.edit', 'event-informations', 'visitors.summary', 'visitors.edit', 'visitor.find-products', 'visitor.wishlists', 'exhibitor.summary', 'appointment.summary', 'hall-layout', 'myappointments', 'exhibitor.directory', 'import.exhibitors', 'sales-person-mapping']);
    $isSalesPerson = isSalesPerson();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg navbar-light border-end border-5">
    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/medicall-logo-min.png') }}" alt="Medicall CRM" width="150" height="100">
            </a>
        </h1>

        <div class=" d-lg-none d-flex justify-content-evenly">
            <div class="nav-item dropdown ">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0 text-secondary" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span>
                        @include('icons.user-circle')
                    </span>
                    <div class="d-xl-block ps-2">
                        @isset(getAuthData()->name)
                            <div>{{ getAuthData()->name ?? '' }}</div>
                        @endisset
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                    <a href="{{ route('user.profile') }}" class="dropdown-item">Account Info</a>
                    {{-- <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit()"
                            class="dropdown-item text-danger">Logout</a>
                    </form> --}}
                </div>
            </div>

            <div class=" d-md-flex " >
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit()"
                        class="text-danger text-decoration-none px-2">
                        <span class="text-danger">
                            @include('icons.logout')
                        </span>
                        Logout
                    </a>
                </form>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch mt-2">
                <ul class="navbar-nav">
                    @if (auth()->guard('exhibitor')->check())
                        <li>
                            @livewire('exhibitor.profile-status')
                        </li>
                    @endif
                    @if (auth()->guard('visitor')->check())
                        <li>
                            @livewire('visitor.profile-status')
                        </li>
                    @endif
                    <li class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    @if (isOrganizer() && !$isPreviousEvent)
                        <li class="nav-item {{ $currentRouteName == 'visitor-registration' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('visitor-registration', request('eventId') ? ['eventId' => request('eventId')] : '') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.user')
                                </span>
                                <span class="nav-link-title">
                                    Add New Visitor
                                </span>
                            </a>
                        </li>


                        <li class="nav-item {{ $currentRouteName == 'exhibitor.registration' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('exhibitor.registration', request('eventId') ? ['eventId' => request('eventId')] : '') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.apps')
                                </span>
                                <span class="nav-link-title">
                                    Add New Exhibitor
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- @auth('exhibitor')
                        @if (in_array(Route::currentRouteName(), ['event-informations', 'myproducts', 'myappointments']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('myproducts', ['eventId' => request('eventId')]) }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-shopping-cart" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M17 17h-11v-14h-2"></path>
                                            <path d="M6 5l14 1l-1 7h-13"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        My Products
                                    </span>
                                </a>
                            </li>
                        @endif
                    @endauth --}}

                    @if (request('eventId') && !$isPreviousEvent)
                        <li class="dropdown {{ $eventOverviewIsActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle {{ $currentRouteName == 'event-informations' ? 'active' : '' }}"
                                href="{{ route('event-informations', ['eventId' => request('eventId')]) }}"
                                data-bs-auto-close="outside" role="button"
                                aria-expanded="{{ $eventOverviewIsActive ? 'ture' : 'false' }}">
                                {{-- href=#navbar-help""> --}}
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.dashboard')
                                </span>
                                <span class="nav-link-title">
                                    Event Overview
                                </span>
                            </a>
                            <div class="dropdown-menu {{ $eventOverviewIsActive ? 'show' : '' }}">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">

                                        {{-- @if (in_array($currentRouteName, [
                                                'event-informations',
                                                'visitors.summary',
                                                'exhibitor.summary',
                                                'appointment.summary',
                                                'hall-layout',
                                                'visitor-registration',
                                                'exhibitor.registration',
                                                'myappointments',
                                                'visitor.find-products',
                                                'visitor.wishlists',
                                                'visitors.edit',
                                                'exhibitor.edit',
                                                'exhibitor.directory',
                                                'import.exhibitors',
                                                'sales-person-mapping',
                                            ]) && request('eventId'))
                                            <a class=" dropdown-item nav-link {{ $currentRouteName == 'hall-layout' ? 'active' : '' }} "
                                                href="{{ route('hall-layout', ['eventId' => request('eventId')]) }}">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    @include('icons.layout')
                                                </span>
                                                <span class="nav-link-title">
                                                    Hall Layout
                                                </span>
                                            </a>
                                        @endif --}}

                                        @auth('visitor')
                                            @if (in_array($currentRouteName, [
                                                    'event-informations',
                                                    'visitor.find-products',
                                                    'myappointments',
                                                    'visitor-registration',
                                                    'exhibitor.registration',
                                                    'hall-layout',
                                                    'visitor.find-products',
                                                    'visitor.wishlists',
                                                    'exhibitor.directory',
                                                    'sales-person-mapping',
                                                ]))
                                                <a class="dropdown-item nav-link {{ $currentRouteName == 'visitor.find-products' ? 'active' : '' }}"
                                                    href="{{ route('visitor.find-products', ['eventId' => request('eventId')]) }}">
                                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                        @include('icons.basket-filled')
                                                    </span>
                                                    <span class="nav-link-title">
                                                        New Appointment
                                                    </span>
                                                </a>

                                                <a class="dropdown-item nav-link {{ $currentRouteName == 'exhibitor.directory' ? 'active' : '' }}"
                                                    href="{{ route('exhibitor.directory', ['eventId' => request('eventId')]) }}">
                                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                        @include('icons.user-filled')
                                                    </span>
                                                    <span class="nav-link-title">
                                                        Exhibitor Directory
                                                    </span>
                                                </a>

                                                <a class="dropdown-item nav-link {{ $currentRouteName == 'visitor.wishlists' ? 'active' : '' }}"
                                                    href="{{ route('visitor.wishlists', ['eventId' => request('eventId')]) }}">
                                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                        @include('icons.wishlist')
                                                    </span>
                                                    <span class="nav-link-title">
                                                        My Wishlists
                                                    </span>
                                                </a>
                                            @endif
                                        @endauth

                                        @if (auth()->guard('visitor')->check() ||
                                                auth()->guard('exhibitor')->check() )
                                            @if (in_array($currentRouteName, [
                                                    'event-informations',
                                                    'myappointments',
                                                    'visitor.find-products',
                                                    'myproducts',
                                                    'visitor-registration',
                                                    'exhibitor.registration',
                                                    'hall-layout',
                                                    'visitor.wishlists',
                                                    'exhibitor.directory',
                                                    'sales-person-mapping',
                                                ]))
                                                <a class="dropdown-item nav-link {{ $currentRouteName == 'myappointments' ? 'active' : '' }}"
                                                    href="{{ route('myappointments', ['eventId' => request('eventId')]) }}">
                                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                        @include('icons.appointment')
                                                    </span>
                                                    <span class="nav-link-title">
                                                        My Appointments
                                                    </span>
                                                </a>
                                            @endif
                                        @endif

                                        @if (auth()->guard('web')->check() &&
                                                in_array($currentRouteName, [
                                                    'event-informations',
                                                    'visitors.summary',
                                                    'exhibitor.summary',
                                                    'appointment.summary',
                                                    'hall-layout',
                                                    'visitor-registration',
                                                    'exhibitor.registration',
                                                    'exhibitor.edit',
                                                    'visitors.edit',
                                                    'sales-person-mapping',
                                                    'exhibitor.directory',
                                                    'myappointments',
                                                    'import.exhibitors',

                                                ]) &&
                                                request('eventId') !== null)
                                            <a class="dropdown-item nav-link  {{ $currentRouteName == 'visitors.summary' || $currentRouteName == 'visitors.edit' ? 'active' : '' }}"
                                                href="{{ route('visitors.summary', ['eventId' => request('eventId')]) }}">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    @include('icons.users-group')
                                                </span>
                                                <span class="nav-link-title">
                                                    Visitors
                                                </span>
                                            </a>
                                            <a class="dropdown-item nav-link {{ $currentRouteName == 'exhibitor.summary' || $currentRouteName == 'exhibitor.edit' ? 'active' : '' }}"
                                                href="{{ route('exhibitor.summary', ['eventId' => request('eventId')]) }}">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    @include('icons.building-skyscraper')
                                                </span>
                                                <span class="nav-link-title">
                                                    Exhibitors
                                                </span>
                                            </a>
                                            <a class="dropdown-item nav-link {{ $currentRouteName == 'appointment.summary' ? 'active' : '' }}"
                                                href="{{ route('appointment.summary', ['eventId' => request('eventId')]) }}">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    @include('icons.calendar-event')
                                                </span>
                                                <span class="nav-link-title">
                                                    Appointments
                                                </span>
                                            </a>
                                        @endif
                                        @if (isOrganizer())
                                            <a class="dropdown-item nav-link {{ $currentRouteName == 'import.exhibitors' ? 'active' : '' }}"
                                                href="{{ route('import.exhibitors', ['eventId' => request('eventId')]) }}">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    @include('icons.file-import')
                                                </span>
                                                <span class="nav-link-title">
                                                    Import Exhibitors
                                                </span>
                                            </a>
                                            @if (!$isSalesPerson)
                                                <a class="dropdown-item nav-link {{ $currentRouteName == 'sales-person-mapping' ? 'active' : '' }}"
                                                    href="{{ route('sales-person-mapping', ['eventId' => request('eventId')]) }}">
                                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                        @include('icons.plug-connected')
                                                    </span>
                                                    <span class="nav-link-title">
                                                        Sales Person Mapping to Exhibitors
                                                    </span>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    @if (isOrganizer())
                        <li class="dropdown {{ $previousEventActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle " href="#navbar-help" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button"
                                aria-expanded="{{ $previousEventActive ? 'true' : 'false' }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.previous-squre-filled')
                                </span>
                                <span class="nav-link-title">
                                    Previous Events
                                </span>
                            </a>

                            <div class="dropdown-menu {{ $previousEventActive && $isPreviousEvent ? 'show' : '' }}">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        {{-- @if (in_array($previousEventActive, ['visitors.summary', 'exhibitor.summary', 'appointment.summary', 'visitors.edit', 'exhibitor.edit', 'admin-dashboard'])) --}}

                                        @foreach ($previousEvents as $event)
                                            <div wire:key='event-{{ $event->id }}'
                                                class="dropend {{ $previousEventActive ? 'show' : '' }}">
                                                <a class="dropdown-item dropdown-toggle "
                                                    href="#sidebar-authentication" data-bs-toggle="dropdown"
                                                    data-bs-auto-close="outside" role="button"
                                                    aria-expanded="false">
                                                    <span class="nav-link-title text-wrap ">
                                                        {{ $event->title ?? 'Previous Event' }}
                                                    </span>
                                                </a>

                                                <div  wire:key='event-{{ $event->id }}'
                                                    class="dropdown-menu {{ $previousEventActive && $isPreviousEvent && $eventId ==  $event->id ? 'show' : '' }}">
                                                    <a class="dropdown-item nav-link  {{ $currentRouteName == 'admin-dashboard' && $isPreviousEvent && $eventId ==  $event->id ? 'active' : '' }}"
                                                        href="{{ route('admin-dashboard', ['eventId' => $event->id]) }}">
                                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                            @include('icons.plus')
                                                        </span>
                                                        <span class="nav-link-title">
                                                            Event Dashboard
                                                        </span>
                                                    </a>
                                                    <a class="dropdown-item nav-link  {{ ($currentRouteName == 'visitors.summary' || $currentRouteName == 'visitors.edit') && $isPreviousEvent && $eventId ==  $event->id ? 'active' : '' }}"
                                                        href="{{ route('visitors.summary', ['eventId' => $event->id]) }}">
                                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                            @include('icons.users-group')
                                                        </span>
                                                        <span class="nav-link-title">
                                                            Visitors
                                                        </span>
                                                    </a>
                                                    <a class="dropdown-item nav-link {{ ($currentRouteName == 'exhibitor.summary' || $currentRouteName == 'exhibitor.edit') && $isPreviousEvent && $eventId ==  $event->id  ? 'active' : '' }}"
                                                        href="{{ route('exhibitor.summary', ['eventId' => $event->id]) }}">
                                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                            @include('icons.building-skyscraper')
                                                        </span>
                                                        <span class="nav-link-title">
                                                            Exhibitors
                                                        </span>
                                                    </a>
                                                    <a class="dropdown-item nav-link {{ $currentRouteName == 'appointment.summary' && $isPreviousEvent && $eventId ==  $event->id   ? 'active' : '' }}"
                                                        href="{{ route('appointment.summary', ['eventId' => $event->id]) }}">
                                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                            @include('icons.calendar-event')
                                                        </span>
                                                        <span class="nav-link-title">
                                                            Appointments
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    @auth('visitor')
                        <li class="nav-item {{ $currentRouteName == 'visitor.profile' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('visitor.profile') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.user')
                                </span>
                                <span class="nav-link-title">
                                    Profile
                                </span>
                            </a>
                        </li>
                    @endauth


                    {{-- @auth('exhibitor')
                        <li class="nav-item {{ $currentRouteName == 'exhibitor.profile' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('exhibitor.profile') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.user')
                                </span>
                                <span class="nav-link-title">
                                    Profile
                                </span>
                            </a>
                        </li>
                    @endauth --}}

                    @if (isOrganizer() && request('eventId') === null)
                        <li class="dropdown {{ $masterMenuIsActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle " href="#navbar-help" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button"
                                aria-expanded="{{ $masterMenuIsActive ? 'true' : 'false' }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    @include('icons.settings')
                                </span>
                                <span class="nav-link-title">
                                    Masters
                                </span>
                            </a>

                            <div class="dropdown-menu {{ $masterMenuIsActive ? 'show' : '' }}">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">

                                        <a class="dropdown-item {{ $currentRouteName == 'employees.index' ? 'active' : '' }}"
                                            href="{{ route('employees.index') }}">
                                            <span class="nav-link-title">
                                                Employees
                                            </span>
                                        </a>

                                        <a class="dropdown-item {{ $currentRouteName == 'visitors.summary' || $currentRouteName == 'visitors.edit' ? 'active' : '' }}"
                                            href="{{ route('visitors.summary') }}">
                                            <span class="nav-link-title">
                                                Visitors
                                            </span>
                                        </a>
                                        <a class="dropdown-item {{ $currentRouteName == 'exhibitor.summary' || $currentRouteName == 'exhibitor.edit' ? 'active' : '' }}"
                                            href="{{ route('exhibitor.summary') }}">
                                            <span class="nav-link-title">
                                                Exhibitors
                                            </span>
                                        </a>

                                        <a class="dropdown-item {{ $currentRouteName == 'events' ? 'active' : '' }}"
                                            href="{{ route('events') }}">
                                            <span class="nav-link-title">
                                                Events
                                            </span>
                                        </a>
                                        <a class="dropdown-item {{ $currentRouteName == 'category' ? 'active' : '' }}"
                                            href={{ route('category') }}>
                                            <span class="nav-link-title">
                                                Categories
                                            </span>
                                        </a>
                                        <a class="dropdown-item {{ $currentRouteName == 'products' ? 'active' : '' }}"
                                            href={{ route('products') }}>
                                            <span class="nav-link-title">
                                                Products
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

    </div>

</aside>
