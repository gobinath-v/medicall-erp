<div class="page-body">
    <div wire:ignore.self class="modal modal-blur fade" id="feedback" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Feedback</h5>
                    <button type="button" class="btn-close" wire:click='closeFeedbackModal'
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea placeholder="Enter Feedback" rows="3" class="form-control" wire:model.defer="feedback"></textarea>
                        @error('feedback')
                            <div class="error text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" wire:click='closeFeedbackModal'>Close</button>
                    <button type="button" class="btn btn-primary" wire:click='appointmentComplete'>Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @include('includes.alerts')
        <div class="card">

            @livewire('appointments-modal')
            <div class="card-header d-flex justify-content-between" id="title">
                <h3 class="card-title fs-5 fw-bold">My Appointments</h3>
                <div class="d-flex pt-1">
                    @if (!auth()->guard('exhibitor')->check())
                        <a class="text fw-bold text-decoration-none me-3 fs-5 pt-2"
                            href="{{ route('visitor.find-products', ['eventId' => $eventId]) }}">
                            Fix Appointment
                        </a>
                    @endif
                    <button id="btn" wire:click="toggleBtn" class="btn btn-secondary btn-md p-2 me-1">
                        <span>@include('icons.filter-search')</span>
                    </button>
                    @if ($toggleContent == true)
                        <a href ="{{ route('myappointments', ['eventId' => $eventId]) }}"
                            class="text-decoration-none fw-bold pt-1"><small>Reset</small></a>
                    @endif
                </div>
            </div>

            @if ($toggleContent == true)
                <div class="card-body d-flex justify-content-between">
                    <div class="col-md-3 me-3">
                        <div class="input-group input-group-flat">
                            <input type="text" class="form-control" wire:model.live="search"
                                placeholder="Search Exhibitors/Visitors">
                            <span class="input-group-text">
                                <span wire:click="resetField"
                                    style="margin-left:-20%">@include('icons.close')</span>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input type="date" class="form-control px-2 ms-2" wire:model.live="dateFilter">
                        <span wire:click="resetDate" class="p-2">@include('icons.close')</span>
                    </div>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap table-striped datatable">
                    <thead>
                        <tr class="text-center">
                            <th>
                                #

                            </th>


                            @if (auth()->guard('exhibitor')->check())
                                <th>
                                    Visitor
                                    <span style="cursor: pointer;" data-toggle="tooltip" data-placement="top"
                                        title="Sort Ascending" wire:click="sortBy('visitor_name','asc')">
                                        @include('icons.arrow-narrow-up')
                                    </span>
                                    <span style="cursor: pointer;" data-toggle="tooltip" data-placement="top"
                                        title="Sort Descending" wire:click="sortBy('visitor_name','desc')">
                                        @include('icons.arrow-narrow-down')
                                    </span>
                                </th>
                                <th>Organization</th>
                                <th>Place</th>
                                <th>Purpose of Meeting</th>
                            @elseif(auth()->guard('visitor')->check())
                                <th>
                                    Exhibitor

                                    <span wire:click="sortBy('exhibitor_name', 'asc')" style="cursor:pointer;"
                                        data-toggle="tooltip" data-placement="top" title="Sort Ascending">
                                        @include('icons.arrow-narrow-up')
                                    </span>
                                    <span wire:click="sortBy('exhibitor_name','desc')" style="cursor:pointer;"
                                        data-toggle="tooltip" data-placement="top" title="Sort Descending">
                                        @include('icons.arrow-narrow-down')</span>

                                </th>
                                <th>Stall No.</th>
                            @endif
                            <th>
                                Scheduled On
                                <span style="cursor: pointer;" data-toggle="tooltip" data-placement="top"
                                    title="Sort Ascending" wire:click="sortBy('scheduled_at','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor: pointer;" data-toggle="tooltip" data-placement="top"
                                    title="Sort Descending" wire:click="sortBy('scheduled_at','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>
                            <th>Post Meeting Notes</th>
                            <th>Status</th>
                            <th class="w-1">Action</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @if (isset($myappointments))
                            @foreach ($myappointments as $myappointmentsIndex => $myappointment)
                                <tr class="text-center" wire:key='{{ $myappointment->id }}'>
                                    <td>
                                        {{ $myappointmentsIndex + $myappointments->firstItem() }}
                                    </td>

                                    @if (auth()->guard('exhibitor')->check())
                                        <td>
                                            <div class='text-capitalize'> {{ $myappointment->visitor->name ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $myappointment->visitor->organization ?? '' }}</strong><br>
                                            <small>{{ $myappointment->visitor->designation ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class='text-capitalize'>
                                                {{ $myappointment->visitor->address->city ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class='text-capitalize'>
                                                @if (strlen($myappointment->notes ?? '') > 15)
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $myappointment->notes ?? '' }}">
                                                        {{ substr($myappointment->notes ?? '', 0, 15) }}...
                                                    </span>
                                                @else
                                                    {{ substr($myappointment->notes ?? '', 0, 15) }}
                                                @endif
                                            </div>
                                        </td>
                                    @elseif(auth()->guard('visitor')->check())
                                        <td>
                                            <div class='text-capitalize'> {{ $myappointment->exhibitor->name ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class='text-capitalize'>
                                                {{ $myappointment->exhibitor->eventExhibitors->first()->stall_no ?? 'NA' }}
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <div class='text-capitalize'>
                                            {{ $myappointment->scheduled_at->isoFormat('llll') ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $feedback = auth()
                                                ->guard('exhibitor')
                                                ->check()
                                                ? $myappointment->_meta['exhibitor_feedback']['message'] ?? ''
                                                : $myappointment->_meta['visitor_feedback']['message'] ?? '';
                                        @endphp
                                        <div class='text-capitalize'>
                                            @if (strlen($feedback ?? '') > 15)
                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $feedback ?? '' }}">
                                                    {{ substr($feedback ?? '', 0, 15) }}...
                                                </span>
                                            @else
                                                {{ substr($feedback ?? '', 0, 15) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div @class([
                                            'text-capitalize',
                                            'badge',
                                            'bg-blue-lt' => $myappointment->status == 'scheduled' ? true : false,
                                            'bg-secondary-lt' => $myappointment->status == 'rescheduled' ? true : false,
                                            'bg-red-lt' =>
                                                $myappointment->status == 'cancelled' ||
                                                $myappointment->status == 'no-show'
                                                    ? true
                                                    : false,
                                            'bg-green-lt' =>
                                                $myappointment->status == 'completed' ||
                                                $myappointment->status == 'confirmed'
                                                    ? true
                                                    : false,
                                        ])>
                                            {{ $myappointment->status == 'scheduled' ? 'pending' : $myappointment->status ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if (auth()->guard('exhibitor')->check() && in_array($myappointment->status, ['scheduled', 'rescheduled']))
                                                <a href="#"
                                                    wire:click='exhibitorAppointmentStatus({{ $myappointment->id }}, "confirmed")'
                                                    title="Confirm" data-toggle="tooltip" data-placement="top">
                                                    @include('icons.calendar-check')
                                                </a>
                                            @endif
                                            @if ($myappointment->status == 'confirmed')
                                                <a href="#"
                                                    wire:click='exhibitorAppointmentId({{ $myappointment->id }}, "completed")'
                                                    title="Completed" data-toggle="tooltip" data-placement="top"
                                                    data-bs-toggle="modal" data-bs-target="#feedback">
                                                    @include('icons.circle-check')
                                                </a>
                                            @endif
                                            @if (in_array($myappointment->status, ['scheduled', 'rescheduled', 'confirmed']))
                                                <a href="#"
                                                    wire:click='getAppointmentId({{ $myappointment->id }})'
                                                    title="Re-Schedule" data-toggle="tooltip" data-placement="top">
                                                    @include('icons.calendar-repeat')
                                                </a>
                                            @endif
                                            @if (auth()->guard('exhibitor')->check() && $myappointment->status == 'confirmed')
                                                <a href="#"
                                                    wire:click.prevent="$dispatch('cancelAppointment', {id:{{ $myappointment->id }},status: 'no-show'})"
                                                    title="No Show" data-toggle="tooltip" data-placement="top">
                                                    @include('icons.calendar-question')
                                                </a>
                                            @endif
                                            @if (in_array($myappointment->status, ['scheduled', 'rescheduled', 'confirmed']))
                                                <a href="#"
                                                    wire:click.prevent="$dispatch('cancelAppointment', {id:{{ $myappointment->id }},status: 'cancelled'})"
                                                    title="Cancel" data-toggle="tooltip" data-placement="top">
                                                    @include('icons.calendar-x')
                                                </a>
                                            @endif
                                            @if (
                                                $myappointment->status == 'completed' &&
                                                    ((auth()->guard('visitor')->check() &&
                                                        empty($myappointment->_meta['visitor_feedback']['message'])) ||
                                                        (auth()->guard('exhibitor')->check() &&
                                                            empty($myappointment->_meta['exhibitor_feedback']['message']))))
                                                <a href="#"
                                                    wire:click='exhibitorAppointmentId({{ $myappointment->id }}, "feedback")'
                                                    title="Post Meeting Notes" data-toggle="tooltip"
                                                    data-placement="top" data-bs-toggle="modal"
                                                    data-bs-target="#feedback">
                                                    @include('icons.message-plus')</a>
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        @if (in_array($myappointment->status, ['scheduled', 'rescheduled', 'confirmed']))
                                            <a href="#"
                                                wire:click.prevent="generateICS({{ $myappointment->id }})"
                                                download="event.ics" title="Download ICS File" data-toggle="tooltip"
                                                data-placement="top">@include('icons.download')</a>
                                            <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ auth()->guard('exhibitor')->check()? $myappointment->visitor->name: $myappointment->exhibitor->name }}&dates={{ $myappointment->scheduled_at->setTimezone('UTC')->format('Ymd\THis\Z') }}/{{ $myappointment->scheduled_at->addHours(2)->setTimezone('UTC')->format('Ymd\THis\Z') }}&details={{ $myappointment->notes }}"
                                                target="_blank" title="Add to Google Calendar" data-toggle="tooltip"
                                                data-placement="top">@include('icons.calendar-clock')</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if (isset($myappointments) && count($myappointments) == 0)
                            @livewire('not-found-record-row', ['colspan' => 10])
                        @endif
                    </tbody>
                </table>
                <div class="d-flex p-2 justify-content-end">
                    @if (isset($myappointments) && count($myappointments) >= 0)
                        {{ $myappointments->links() }}
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('closeModal', function() {
                $('#modal-report').modal('hide');
            });
            Livewire.on('openModel', (appointmentId) => {
                $('#modal-report').modal('show');

            });
            Livewire.on('closeFeedbackModal', function() {
                $('#feedback').modal('hide');
            });
            Livewire.on('cancelAppointment', (appointment) => {
                if (confirm('Are you sure you want to Cancel/No show this Appointment ?')) {
                    @this.exhibitorAppointmentStatus(appointment.id, appointment.status);
                }
            });
        });
    </script>
@endpush
