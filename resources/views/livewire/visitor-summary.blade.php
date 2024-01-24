<div>
    <div class="page-body">
        @if (isset($eventId))
            @livewire('appointments-modal')
        @endif
        <div class="container-xl">
            @include('includes.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <h3>List of Visitors</h3>
                    <div class="card">
                        <div class="card-header d-flex justify-content-end">
                            @if (!isset($eventId))
                                @if ($showToggle == true)
                                    <div class="d-flex gap-1 mb-2 pt-2">
                                        <select class="form-select" wire:model.live="event_id">
                                            <option value="">Select Event</option>
                                            @foreach ($events as $eventID => $eventTitle)
                                                <option value="{{ $eventID }}">{{ $eventTitle }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn text-white" style="background-color: #f1a922;"
                                            wire:click="selectedVisitorsId"
                                            {{ empty($event_id) ? 'disabled' : '' }}>Add</button>
                                    </div>
                                @endif
                                <a href="#" class="mb-2 text-decoration-none pe-3" data-bs-toggle="tooltip"
                                    title="Move to Another Event"
                                    wire:click="toggleEvents">@include('icons.cloud-upload')</a>
                            @endif
                            <div class="input-group input-group-flat w-25">
                                <input type="text" wire:model.live="search" value="" class="form-control"
                                    placeholder="Search…">
                                <span class="input-group-text pe-3">
                                    <a href="#" wire:click="$set('search', '')" class="link-secondary"
                                        title="Clear search" data-bs-toggle="tooltip">
                                        @include('icons.close')
                                    </a>
                                </span>

                            </div>

                            <div class="col-auto ps-2">
                                <button class="btn w-10" wire:click="exportToExcel" wire:loading.attr="disabled"
                                    {{ isset($visitors) && count($visitors) == 0 ? 'disabled' : '' }}>
                                    @include('icons.file-export')
                                    <span wire:loading wire:target="exportToExcel">Exporting...</span>
                                    <span wire:loading.remove wire:target="exportToExcel">Export to Excel</span>
                                </button>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" style="width: 140%">
                                <thead>
                                    <tr>
                                        @if (!isset($eventId))
                                            <th>
                                                <div>
                                                    <label class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model.live="selectAll"
                                                            style="border-color:rgb(134, 132, 132);">
                                                    </label>
                                                </div>
                                            </th>
                                        @endif
                                        <th>#</th>
                                        <th>Name
                                            <span wire:click.prevent="sortColumn('name','asc')" style="cursor:pointer;"
                                                data-toggle="tooltip" data-placement="top" title="Sort Ascending">
                                                @include('icons.arrow-narrow-up')
                                            </span>
                                            <span wire:click.prevent="sortColumn('name','desc')" style="cursor:pointer;"
                                                data-toggle="tooltip" data-placement="top" title="Sort Descending">
                                                @include('icons.arrow-narrow-down')
                                            </span>
                                        </th>

                                        <th>Mobile.No.
                                            <span wire:click.prevent="sortColumn('mobile_number','asc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Ascending">
                                                @include('icons.arrow-narrow-up')
                                            </span>
                                            <span wire:click.prevent="sortColumn('mobile_number','desc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Descending">
                                                @include('icons.arrow-narrow-down')
                                            </span>
                                        </th>
                                        <th>Email
                                            <span wire:click.prevent="sortColumn('email','asc')" style="cursor:pointer;"
                                                data-toggle="tooltip" data-placement="top" title="Sort Ascending">
                                                @include('icons.arrow-narrow-up')
                                            </span>
                                            <span wire:click.prevent="sortColumn('email','desc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Descending">
                                                @include('icons.arrow-narrow-down')
                                            </span>
                                        </th>
                                        <th>Nature of Business</th>
                                        <th>Organization
                                            <span wire:click.prevent="sortColumn('organization','asc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Ascending">
                                                @include('icons.arrow-narrow-up')
                                            </span>
                                            <span wire:click.prevent="sortColumn('organization','desc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Descending">
                                                @include('icons.arrow-narrow-down')
                                            </span>
                                        </th>


                                        <th>Designation</th>
                                        <th>Reason for Visit</th>
                                        <th>Product Looking for</th>
                                        <th>No of Appointments
                                            <span wire:click.prevent="sortColumn('appointments_count','asc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Ascending">
                                                @include('icons.arrow-narrow-up')
                                            </span>
                                            <span wire:click.prevent="sortColumn('appointments_count','desc')"
                                                style="cursor:pointer;" data-toggle="tooltip" data-placement="top"
                                                title="Sort Descending">
                                                @include('icons.arrow-narrow-down')
                                            </span>
                                        </th>
                                        <th class="w-2"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($visitors) && count($visitors) > 0)
                                        @foreach ($visitors as $visitorsIndex => $visitor)
                                            <tr wire:key="{{ $visitor->id }}">
                                                @if (!isset($eventId))
                                                    <td>
                                                        <div>
                                                            <label class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    wire:model="selectedVisitors"
                                                                    value="{{ $visitor->id }}"
                                                                    style="border-color:rgb(134, 132, 132);">
                                                            </label>
                                                        </div>
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $visitorsIndex + $visitors->firstItem() }}
                                                </td>
                                                <td>
                                                    <div class="text-capitalize small lh-base">{{ $visitor->name }}
                                                    </div>

                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize small lh-base">
                                                        {{ $visitor->mobile_number }}</div>
                                                </td>
                                                <td>
                                                    <div class="small lh-base">{{ strtolower($visitor->email) }}

                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="text-capitalize small lh-base">
                                                        {{ $visitor->category->name ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize small lh-base">
                                                        {{ $visitor->organization }}</div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize small lh-base">
                                                        {{ $visitor->designation }}</div>
                                                </td>
                                                <td style="max-width: 100px; overflow: hidden;">
                                                    <div class="text-capitalize small lh-base">
                                                        {{ $visitor->reason_for_visit }}</div>
                                                </td>

                                                <td style="max-width: 100px; overflow: hidden;">
                                                    <div class="text-capitalize small lh-base">
                                                        @foreach ($visitor->eventVisitors as $eventVisitor)
                                                            @php
                                                                $productNames = $eventVisitor->getProductNames();
                                                                $productArray = explode(',', $productNames);
                                                                $productCount = count($productArray);
                                                            @endphp
                                                            @if ($productCount > 0)
                                                                {{ implode(', ', array_slice($productArray, 0, 3)) }}
                                                                @if ($productCount > 3)
                                                                    <a href="#" data-bs-toggle="tooltip"
                                                                        title="{{ $productNames }}" class="fs-5">
                                                                        <br>+{{ $productCount - 3 }} more
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize small lh-base">
                                                        @if (isset($eventId) && $visitor->appointments->where('event_id', $eventId)->count() > 0)
                                                            {{ $visitor->appointments->where('event_id', $eventId)->count() }}
                                                        @elseif(!isset($eventId) && $visitor->appointments->count() > 0)
                                                            {{ $visitor->appointments->count() }}
                                                        @else
                                                            No Appointments
                                                        @endif
                                                    </div>
                                                </td>



                                                <td>
                                                    @php
                                                        $previousEvents = getPreviousEvents();
                                                        $isPreviousEvent = in_array($eventId, $previousEvents->pluck('id')->toArray());
                                                    @endphp
                                                    @if (!$isPreviousEvent)
                                                        <div class="d-flex">
                                                            <a href="{{ route('visitors.edit', ['visitorId' => $visitor->id, 'eventId' => $eventId]) }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Edit Visitor">
                                                                @include('icons.edit')
                                                            </a>

                                                            <a class="ps-3" href="#"
                                                                wire:click="getVisitor({{ $visitor->id }})"
                                                                data-bs-toggle="modal" data-bs-target="#modal-report"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Make Appointment">
                                                                @include('icons.appointment')
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                    @if (isset($visitors) && count($visitors) == 0)
                                        @livewire('not-found-record-row', ['colspan' => 12])
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                {{ $visitors->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('closeModal', function() {
                $('#modal-report').modal('hide');
            });
        });
    </script>
@endpush
