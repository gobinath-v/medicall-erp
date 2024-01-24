<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Medicall CRM</title>

        <!-- CSS files -->
        <link href="{{ asset('/theme/css/tabler.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('/theme/css/tabler-vendors.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('/theme/css/demo.min.css') }}" rel="stylesheet" />

        <style>
            @import url('https://rsms.me/inter/inter.css');

            :root {
                --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            }

            body {
                font-feature-settings: "cv03", "cv04", "cv11";
            }
        </style>
        @livewireStyles
        @stack('styles')
    </head>
    <body>
        @include('layouts.partials.admin-header')

        <div class="page">

            <div class="page-wrapper">

                @yield('content')

                @if (isset($slot))
                {{ $slot }}
                @endif

                {{-- @include('layouts.partials.admin-footer') --}}
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="{{ asset('theme/js/tabler.min.js') }}" defer></script>
        <script src="{{ asset('theme/js/demo.js') }}" defer></script>
        <script src="{{ asset('theme/js/demo-theme.min.js') }}"></script>
        @stack('modals')
        @livewireScripts
        @stack('scripts')
        <script>
            jQuery(document).ready(function() {
                jQuery('[data-toggle="tooltip"]').tooltip();
            });
        </script>


    </body>
</html>
