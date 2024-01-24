<div>
    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')
            <div class="row">
                <div class="col-lg-4">
                    @livewire('events-handler', ['eventId' => $eventId])
                </div>
                <div class="col-lg-8">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div>
                            <h4>List all Events</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Thumbnail</th>
                                        <th>Event Edition</th>
                                        <th>Event Dates</th>
                                        <th>Address</th>
                                        <th colspan="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($events) && count($events) > 0)
                                        @foreach ($events as $eventIndex => $event)
                                            <tr wire:key='item-{{ $event->id }}'>
                                                <td>
                                                    {{ $eventIndex + $events->firstItem() }}
                                                </td>
                                                <td>
                                                    <div class="position-relative">
                                                        @php
                                                            $eventImagePath = $event->_meta['thumbnail'] ?? 'thumbnail/2023/11/medicall-logo-min.png';
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $eventImagePath) }}"
                                                            class="rounded-circle avatar-xl" height="30"
                                                            width="30" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize">
                                                        @php
                                                            $previousEvents = getPreviousEvents();
                                                        @endphp
                                                        @if (in_array($event->id, $previousEvents->pluck('id')->toArray()))
                                                            <a class="text-decoration-none text-yellow"
                                                                href="{{ route('admin-dashboard', ['eventId' => $event->id]) }}">{{ $event->title ?? '' }}
                                                            </a>
                                                        @else
                                                            {{ $event->title ?? '' }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize">
                                                        @php
                                                            $start = \Carbon\Carbon::parse($event->start_date);
                                                            $end = \Carbon\Carbon::parse($event->end_date);
                                                            $difference = $end->diffInDays($start) + 1;
                                                            $dateList = [];
                                                            for ($date = $start; $date->lte($end); $date->addDay()) {
                                                                $dateList[] = $date->format('M-d');
                                                            }

                                                        @endphp

                                                        {{ implode(', ', $dateList) }}
                                                        <span class="badge bg-cde3bc"
                                                            style="background-color: #306269;"><strong>{{ $difference == 1 ? $difference . 'day' : $difference . 'days' }}</strong></span>

                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="text-capitalize">{{ $event->address->address ?? '' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">

                                                        <a
                                                            href="{{ route('events', ['eventId' => $event->id, 'page' => $this->paginators['page'], 'pp' => $this->perPage]) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-edit" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path
                                                                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                                </path>
                                                                <path
                                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                                </path>
                                                                <path d="M16 5l3 3"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="#"
                                                            wire:click.prevent="$dispatch('canDeleteEvent',{{ $event->id }})"
                                                            class="text-danger"
                                                            style="pointer-events:{{ $exhibitorEventsExists[$event->id] == true || $visitorEventsExists[$event->id] == true ? 'none' : 'auto' }};">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-trash"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M4 7l16 0"></path>
                                                                <path d="M10 11l0 6"></path>
                                                                <path d="M14 11l0 6"></path>
                                                                <path
                                                                    d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                                </path>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                                                </path>
                                                            </svg>
                                                        </a>
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


                        <div class="card-footer">
                            <div class="row d-flex flex-row mb-3">
                                @if (isset($events) && count($events) != 0)
                                    <div class="col">
                                        <div class="d-flex flex-row mb-3">
                                            <div>
                                                <label class="p-2" for="perPage">Per Page</label>
                                            </div>
                                            <div>
                                                <select class="form-select" id="perPage" name="perPage"
                                                    wire:model="perPage"
                                                    wire:change="changePageValue($event.target.value)">
                                                    <option value=10>10</option>
                                                    <option value=50>50</option>
                                                    <option value=100>100</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col d-flex justify-content-end">
                                    @if (isset($events) && count($events) >= 0)
                                        {{ $events->links() }}
                                    @endif
                                </div>
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
        Livewire.on('canDeleteEvent', (eventId) => {
            if (confirm('Are you sure to delete this Event ?')) {
                Livewire.dispatch('deleteEvent', {
                    eventId
                });
            }
        });
    </script>
@endpush
