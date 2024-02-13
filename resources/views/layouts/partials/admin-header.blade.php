@php
    $currentRouteName = Route::currentRouteName();
    $event = getCurrentEvent();
@endphp
<header class="navbar navbar-expand-md d-none navbar-light d-lg-flex d-print-none">
    <div class="container-xl ">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        @if (
            !(auth()->guard('web')->check() ||
                auth()->guard('exhibitor')->check() ||
                auth()->guard('visitor')->check()
            ))
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 mx-auto">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/medicall-logo-min.png') }}" alt="Medicall CRM" width="150" height="100">
                </a>
            </h1>
        @else
            <div class="collapse navbar-collapse  d-flex justify-content-between" id="navbar-menu">

                <span class="fw-bold text-danger fs-2 d-flex align-items-center" style="padding-left: 15%">
                    {{ $currentRouteName != 'dashboard.user' ? $event->title : '' }}
                    <small class="badge bg-secondary-lt fs-6 ms-2">
                        @if (auth()->guard('exhibitor')->check())
                            Exhibitor
                        @elseif(auth()->guard('visitor')->check())
                            Visitor
                        @else
                            Admin
                        @endif
                    </small>
                </span>


                <div class="navbar-nav">

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0 text-secondary"
                            data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span>
                                @include('icons.user-circle')
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                @isset(getAuthData()->name)
                                    <div>{{ getAuthData()->name ?? '' }}</div>
                                @endisset
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                            <a href="{{ route('user.profile') }}" class="dropdown-item">Account Info</a>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit()"
                                    class="dropdown-item text-danger">Logout</a>
                            </form>
                        </div>
                    </div>

                    <div class="d-none d-md-flex p-3 ">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit()"
                                class="text-danger text-decoration-none">
                                <span class="text-danger">
                                    @include('icons.logout')
                                </span>
                                Logout
                            </a>
                        </form>
                    </div>

                </div>
            </div>
        @endif
    </div>
</header>
