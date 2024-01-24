@push('styles')
    <style>
        .counts {
            color: #f1a922;
        }

        .counts:hover {
            background-color: #f1a922;
            color: #fff !important;
        }
    </style>
@endpush
<div>
    {{-- <div class="page-header d-print-none">
        <div class="container-xl">

        </div>
    </div> --}}

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                @include('includes.alerts')
                <div class="col-12">
                    <div class="row row-cards">
                        @if (isOrganizer())
                            <div class="row g-2 align-items-center">
                                <div class="col-auto ms-auto d-print-none">
                                    <div class="btn-list">
                                        <a href="{{ route('exhibitor.registration', ['eventId' => $eventId]) }}"
                                            class="btn btn-warning d-none d-sm-inline-block">
                                            @include('icons.plus')
                                            Add New Exhibitor
                                        </a>
                                        <a href="{{ route('visitor-registration', ['eventId' => $eventId]) }}"
                                            class="btn btn-primary d-none d-sm-inline-block">
                                            @include('icons.plus')
                                            Add New Visitor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-orange fw-bold fs-3">Appointment Status</span>
                            @if (auth()->guard('visitor')->check())
                                <a href="{{ route('visitor.find-products', ['eventId' => $eventId]) }}" class="btn btn-sm align-content-end custom-btn"

                                    style="background-color: #f1a922; color: rgb(250, 243, 243);">
                                    @include('icons.calender-plus')
                                    <span style="margin-right:14px; padding-left:10px;"> Book Appointment </span>
                                </a>
                            @endif
                        </div>


                        {{-- <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-primary text-white avatar">
                                                @include('icons.users-group')
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                Visitors
                                            </div>
                                            <div class="text-secondary">
                                                <b>{{ $event->visitors_count ?? 0 }}</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-green text-white avatar">
                                                @include('icons.building-skyscraper')
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                Exhibitors
                                            </div>
                                            <div class="text-secondary">
                                                <b>{{ $event->exhibitors_count ?? 0 }}</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-sm-6 col-lg-3">
                            <a class="text-decoration-none"
                                href="{{ isOrganizer() ? route('appointment.summary', ['eventId' => $eventId]) : route('myappointments', ['eventId' => $eventId]) }}">
                                <div class="card card-sm counts">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="font-weight-medium ps-3  fw-bold">
                                                    Total Appointments
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="avatar bg-gray">
                                                    <b>{{ $appointmentCount ?? 0 }}</b>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- @if (auth()->guard('visitor')->check() ||
    isOrganizer())
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="font-weight-medium ps-3 text-cyan fw-bold">
                                                    Total Seminars
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="avatar {{ getRandomBackgroundColor() }}">
                                                    <b>{{ $event->seminarsCount ?? 0 }}</b>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif --}}

                        @if (auth()->guard('visitor')->check() ||
                                auth()->guard('exhibitor')->check())
                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'scheduled']) }}">
                                    <div class="card card-sm counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3 fw-bold">
                                                        Pending
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b> {{ $scheduledCount ?? 0 }} </b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'rescheduled']) }}">
                                    <div class="card card-sm counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3  fw-bold">
                                                        Re-scheduled
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b>{{ $rescheduledCount ?? 0 }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'confirmed']) }}">
                                    <div class="card card-sm counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3 fw-bold">
                                                        Confirmed
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b> {{ $confirmedCount ?? 0 }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'no-show']) }}">
                                    <div class="card card-sm counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3  fw-bold">
                                                        Lapsed
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b> {{ $lapsedCount ?? 0 }} </b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'cancelled']) }}">
                                    <div class="card card-sm counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3  fw-bold">
                                                        Cancelled
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b>{{ $cancelledCount ?? 0 }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="text-decoration-none"
                                    href="{{ route('myappointments', ['eventId' => $eventId, 'appointmentStatus' => 'completed']) }}">
                                    <div class="card card-sm  counts">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="font-weight-medium ps-3 fw-bold">
                                                        Completed
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="avatar bg-gray">
                                                        <b>{{ $completedCount ?? 0 }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            @if (auth()->guard('exhibitor')->check())
                <div class="col-lg-12 pt-4">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div>
                            <h4 class="text text-orange">Pending Appointments</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Visitor Name</th>
                                        <th>Organization</th>
                                        <th>Place</th>
                                        <th>Purpose of Meeting</th>
                                        <th>Schedule Date & Time</th>
                                        <th>Confirm Appointment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($pendingAppointments) && count($pendingAppointments) > 0)
                                        @foreach ($pendingAppointments as $appointmentIndex => $pendingAppointment)
                                            <tr wire:key='item-{{ $pendingAppointment->id }}'>
                                                <td>
                                                    {{ $appointmentIndex + $pendingAppointments->firstItem() }}
                                                </td>

                                                <td>
                                                    <div class="text-capitalize">
                                                        {{ $pendingAppointment->visitor->name }}</div>
                                                </td>
                                                <td>
                                                    <strong>{{ $pendingAppointment->visitor->organization ?? '' }}</strong><br>
                                                    <small>{{ $pendingAppointment->visitor->designation ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <div class='text-capitalize'>
                                                        {{ $pendingAppointment->visitor->address->city ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <div class='text-capitalize' data-bs-toggle="tooltip"
                                                        title="{{ $pendingAppointment->notes ?? '' }}">
                                                        {{ substr($pendingAppointment->notes ?? '', 0, 10) . (strlen($pendingAppointment->notes ?? '') > 10 ? '...' : '') }}
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="text-capitalize">
                                                        {{ $pendingAppointment->scheduled_at->isoFormat('llll') ?? '' }}
                                                    </div>
                                                </td>

                                                <td>
                                                    <a href="javascript:void(0);"
                                                        wire:click="confirmAppointment({{ $pendingAppointment->id }})">
                                                        @include('icons.squrebox')
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if (isset($pendingAppointments) && count($pendingAppointments) == 0)
                                        @livewire('not-found-record-row', ['colspan' => 7])
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
