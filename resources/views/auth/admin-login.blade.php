<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Medicall CRM' }}</title>
    <!-- CSS files -->
    <link href="{{ asset('/theme/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/theme/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/theme/css/demo.min.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body>

    <div class=" d-flex flex-column">
        <div class="page page-center">
            <div class="container container-normal py-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg">
                        <div class="container-tight ">
                            <div class="text-center mb-4">
                                <a href="#" class="navbar-brand navbar-brand-autodark"></a>
                            </div>
                            <div class="card card-md">
                                <div class="card-body">
                                    <h1 class="h1 text-center mb-2">Medicall Meet</h1>
                                    <h3 class="h3 text-center mb-4">Login to your account</h3>
                                    @include('includes.alerts')
                                    <form method="POST" action="{{ route('admin.login') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Email/Mobile</label>
                                            <input type="text" class="form-control" name="email"
                                                placeholder="Enter email or mobile number"
                                                value="{{ $mobileNo ?? '' }}">
                                        </div>

                                        <div class="mb-2" id="field__password">
                                            <label class="form-label">
                                                Password
                                            </label>

                                            <div class="input-group input-group-flat" id="field__password">

                                                <input type="password" name="password" class="form-control"
                                                    id="password" placeholder="Your password" autocomplete="off">

                                                <span class="input-group-text">
                                                    <a href="#"
                                                        class="link-secondary password--visibility-icons password--show"
                                                        data-target="password--hide" data-action="show"
                                                        title="Show password" data-bs-toggle="tooltip">
                                                        @include('icons.eye')
                                                    </a>
                                                    <a class="link-secondary d-none password--visibility-icons password--hide"
                                                        data-target="password--show" data-action="hide"
                                                        title="Hide password" data-bs-toggle="tooltip">
                                                        @include('icons.eye-off')
                                                    </a>
                                                </span>
                                            </div>
                                        </div>


                                        <div class="form-footer mt-3">
                                            <button type="submit" id="sign-in-form" class="btn btn-primary w-100 ">Sign
                                                in</button>
                                        </div>
                                    </form>

                                    {{-- <div class="text-center mx-auto  mt-3">
                                        <a href="https://crm.medicall.in/" ><span>
                                            @include('icons.home')
                                            Home Page
                                            </span></a>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="{{ asset('theme/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('theme/js/demo-theme.min.js') }}"></script>
    @livewireScripts

    <script>
        jQuery(document).ready(function() {

            jQuery('.sign-in-controls').on("click", function() {
                const target = jQuery(this).attr('data-target');
                const showButtonId = jQuery(this).attr('data-show-target-button');
                const hideButtonId = jQuery(this).attr('data-hide-target-button');
                const targetInputField = jQuery(this).attr('data-target-input-field');
                const targetInputToggleField = jQuery(this).attr('data-taget-input-toggle-field');

                jQuery(target).removeClass('d-none');
                jQuery(this).addClass('d-none');
                jQuery(showButtonId).removeClass('d-none');
                jQuery(hideButtonId).addClass('d-none');
                jQuery(targetInputField).removeClass('d-none');
                jQuery(targetInputToggleField).addClass('d-none');

            });

            jQuery(".password--visibility-icons").on('click', function() {
                let target = jQuery(this).data('target');
                let action = jQuery(this).data('action');
                jQuery(this).toggleClass('d-none');
                jQuery('.' + target).toggleClass('d-none');
                if (action == 'show') {
                    jQuery('input[name="password"]').attr('type', 'text');
                } else {
                    jQuery('input[name="password"]').attr('type', 'password');
                }
            });


        });
    </script>

</body>

</html>
