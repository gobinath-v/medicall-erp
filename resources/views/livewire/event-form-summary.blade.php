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
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards justify-content-between">
            @include('includes.alerts')
            {{-- <div class="row border"> --}}

            <div class="row col-md-12 pt-3 d-flex justify-content-center ">

                <div class="col-md-10 card">

                    <div class="row col-md-12 d-flex ">
                        <div class="{{ isOrganizer() ? 'col-md-6' : 'col-md-12' }}">
                            <h2 class="text-center text-danger fw-bold pt-3">
                                <strong>
                                    {{ (isset($eventId) && !empty($eventId)) ? 'Previous Event' : 'Current Event' }}
                                </strong>
                            </h2>

                                <div class="mx-auto">

                                    <a style="text-decoration: none;"
                                        href="{{ isset($eventId) && !empty($eventId)
                                            ? '#'
                                            : (auth()->guard('web')->check() ||
                                            (auth()->guard('exhibitor')->check()
                                                ? isset($exibitorRegisteredEvents[$currentEvent->id]) && $exibitorRegisteredEvents[$currentEvent->id] == true
                                                : isset($visitorRegisteredEvents[$currentEvent->id]) && $visitorRegisteredEvents[$currentEvent->id] == true)
                                                ? route('event-informations', ['eventId' => $currentEvent->id])
                                                : '#') }}">
                                        <div class="card shadow-sm rounded">
                                            <div class="card-body text-center ">
                                                <h3 class="mx-auto">{{  $currentEvent->title ?? 'Current Event Title' }}
                                                </h3>
                                                <div class="mx-auto">
                                                    <img src="{{ asset('storage/' . ($currentEvent['_meta']['thumbnail'] ?? '')) }}"
                                                        class="avatar-xl" style="background-color: #fff" height="140"
                                                        width="140" />
                                                </div>
                                                @if (auth()->guard('exhibitor')->check())
                                                    @if (isset($exibitorRegisteredEvents[$currentEvent->id]) && $exibitorRegisteredEvents[$currentEvent->id] == true)
                                                        <small class="fs-6 badge bg-green float-end">Registered</small>
                                                    @else
                                                        <button type="button"
                                                            class=" text fs-6 badge bg-blue-lt small float-end"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#registerModal{{ $currentEvent->id }}">
                                                            Register
                                                        </button>
                                                    @endif
                                                @endif

                                                @if (auth()->guard('visitor')->check())

                                                    @if (isset($visitorRegisteredEvents[$currentEvent->id]) && $visitorRegisteredEvents[$currentEvent->id] == true)
                                                        <small class="fs-6 badge bg-green float-end">Registered</small>
                                                    @else
                                                        <button type="button"
                                                            class=" text fs-6 badge bg-blue-lt small float-end"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#registerModal{{$currentEvent->id }}">
                                                            Register
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                @if (!isOrganizer())
                                    <div class="modal" id="registerModal{{ $currentEvent->id }}" tabindex="-1">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Register</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <span>Do you want to register this event?</span>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">No</button>
                                                    @if (auth()->guard('visitor')->check())
                                                        <button type="button" data-bs-dismiss="modal"
                                                            class="btn btn-primary"
                                                            wire:click="registerVisitor({{ $currentEvent->id }})">Yes</button>
                                                    @elseif (auth()->guard('exhibitor')->check())
                                                        <button type="button" data-bs-dismiss="modal"
                                                            class="btn btn-primary"
                                                            wire:click="registerExhibitor({{ $currentEvent->id }})">Yes</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                        </div>

                        @if (isOrganizer())
                            <div class="row col-md-6 pt-4 d-flex align-items-center flex-column  ">

                                <div class="card col-md-6">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId]) }}"
                                        class="text-decoration-none ">
                                        <div class="row d-flex counts" style="padding:5%">
                                            <strong class="text-capitalize w-75 pt-2 fw-bold ">Appointment</strong>
                                            <span class="avatar mx-auto bg-gray">
                                                <b>{{ $appointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="card col-md-6 mt-3 mb-3">
                                    <a href="{{ route('exhibitor.summary', ['eventId' => $currentEventId]) }}"
                                        class="text-decoration-none ">
                                        <div class="row d-flex counts" style="padding:5%">
                                            <strong class="text-capitalize w-75  pt-2 fw-bold ">Exhibitor</strong>
                                            <span class="avatar mx-auto bg-gray">
                                                <b>{{ $exhibitorCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="card col-md-6 ">
                                    <a href="{{ route('visitors.summary', ['eventId' => $currentEventId]) }}"
                                        class="text-decoration-none ">
                                        <div class="row d-flex counts" style="padding:5%">
                                            <strong class="text-capitalize w-75 pt-2 fw-bold ">Visitor</strong>
                                            <span class="avatar mx-auto bg-gray">
                                                <b>{{ $visitorCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (isOrganizer())
                        <div class=" row col-md-12 pt-3 ">

                            <h3 class=" text text-danger fw-bold  ">Appointment Status</h3>

                            <div class="col-md-12 d-flex justify-content-around">

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'scheduled']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small
                                                class="text-capitalize w-50 text-center pt-2 fw-bold mx-auto ">Pending</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $scheduledAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'rescheduled']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small class="text-capitalize text-center pt-2 fw-bold mx-auto"
                                                style="width:58%">Re-scheduled</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $rescheduledAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'confirmed']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small
                                                class="text-capitalize w-50 text-center pt-2 fw-bold mx-auto ">Confirmed</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $confirmedAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                            </div>

                            <div class="col-md-12 pt-3 d-flex justify-content-around">

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'no-show']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small
                                                class="text-capitalize w-50 text-center pt-2 fw-bold mx-auto ">Lapsed</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $lapsedAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'cancelled']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small
                                                class="text-capitalize w-50 text-center pt-2 fw-bold mx-auto ">Cancelled</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $cancelledAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="border card col-md-3">
                                    <a href="{{ route('appointment.summary', ['eventId' => $currentEventId, 'appointmentStatus' => 'completed']) }}"
                                        class="text-decoration-none counts">
                                        <div class="row d-flex " style="padding:6%">
                                            <small
                                                class="text-capitalize w-50 text-center pt-2 fw-bold mx-auto ">Completed</small>
                                            <span class="border avatar mx-auto bg-gray">
                                                <b>{{ $completedAppointmentCount ?? 0 }}</b>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                            </div>


                        </div>

                        <div class="row col-md-12 d-flex justify-content-between">
                            <div class="col-md-5 mt-3 card">
                                <div class="card-header d-flex  justify-content-between">
                                    <h3 class="text text-danger pt-2">Exhibitor List</h3>
                                    <a href='{{ route('exhibitor.summary', ['eventId' => $currentEventId]) }}'
                                        class="text-decoration-none">See
                                        All</a>
                                </div>
                                <div class="col-md-12">
                                    {{-- <div class="align-items-center"> --}}
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <tbody>
                                                @if (isset($exhibitors) && count($exhibitors) > 0)
                                                    @foreach ($exhibitors as $exhibitor)
                                                        <tr wire:key='item-{{ $exhibitor->id }}'>
                                                            <td class="w-25">
                                                                <a href="#">
                                                                    <span
                                                                        class="avatar {{ getRandomBackgroundColor() }}">
                                                                        {{ getNameFirstChars($exhibitor->exhibitor->name ?? '') }}
                                                                    </span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="text-capitalize">
                                                                    <strong>{{ $exhibitor->exhibitor->name ?? '' }}</strong>
                                                                </div>
                                                                <small>{{ $exhibitor->exhibitor->exhibitorContact->name ?? '' }}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @livewire('not-found-record-row', ['colspan' => 6])
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>


                            {{-- <div class="col-md-4 mt-3 card">
                                    <div class="card-header d-flex  justify-content-between">
                                        <h3 class="text text-danger pt-2">Visitor List</h3>
                                        <a href='{{ route('visitors.summary') }}' class="text-decoration-none">See
                                            All</a>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-vcenter card-table">
                                                <tbody>
                                                    @if (isset($visitors) && count($visitors) > 0)
                                                        @foreach ($visitors as $visitor)
                                                            <tr wire:key='item-{{ $visitor->id }}'>
                                                                <td class="w-25">
                                                                    <a href="#">
                                                                        <span
                                                                            class="avatar {{ getRandomBackgroundColor() }}">
                                                                            {{ getNameFirstChars($visitor->name ?? '') }}
                                                                        </span>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div class="text-capitalize">
                                                                        <strong>{{ $visitor->name }}</strong>
                                                                    </div>
                                                                    <small>{{ $visitor->username }}</small>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        @livewire('not-found-record-row', ['colspan' => 6])
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> --}}


                            <div class="col-md-6 mt-3 card">
                                <div class="card-header d-flex  justify-content-between">
                                    <h3 class="text text-danger pt-2">Appointment List</h3>
                                    <a href='{{ route('appointment.summary', ['eventId' => $currentEventId]) }}'
                                        class="text-decoration-none">See
                                        All</a>
                                </div>

                                <div class="col-md-12">
                                    {{-- <div class="align-items-center"> --}}
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <tbody>
                                                @if (isset($appointments) && count($appointments) > 0)
                                                    @foreach ($appointments as $appointment)
                                                        <tr wire:key='item-{{ $appointment->id }}'>
                                                            <td class="w-25">
                                                                <a href="#">
                                                                    <span
                                                                        class="avatar {{ getRandomBackgroundColor() }}">
                                                                        {{ getNameFirstChars($appointment->visitor->name ?? '') }}
                                                                    </span>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="text-capitalize">
                                                                    <strong>{{ $appointment->visitor->name }}</strong>
                                                                </div>
                                                                <small>{{ $appointment->exhibitor->name }}</small>
                                                            </td>
                                                            <td>
                                                                <div class="text-capitalize">
                                                                    {{ $appointment->scheduled_at->isoFormat('llll') }}
                                                                </div>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @livewire('not-found-record-row', ['colspan' => 6])
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="col-md-2  ps-3">
                    <h3 class="text text-danger fw-bold card-header text-center">Upcoming Events</h3>

                    <div class="col-md-12 ">
                        <div class="align-items-center  ">
                            @foreach ($upcomingEventThumbnails as $thumbnail)
                                @php
                                    $eventImagePath = $thumbnail['_meta']['thumbnail'] ?? 'thumbnail/2023/11/medicall-logo-min.png';
                                @endphp
                                <div class="card align-items-center mt-3">
                                    <img src="{{ asset('storage/' . $eventImagePath) }}" class=" avatar-xl"
                                        height="90" width="90" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- </div> --}}
        </div>
    </div>
</div>
