<div class="page-body">
    <div wire:ignore.self class="modal modal-blur fade" id="stall_allocate" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Allocate Stall</h5>
                    <button type="button" class="btn-close" wire:click="clearError" aria-label="Close"></button>
                </div>
                <form wire:submit="updateStallDetail">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="space" class="form-label required">Space</label>
                                <select id="space" class="form-select" wire:model="stall_space">
                                    <option value="">Select Space</option>
                                    <option value="Shell Space">Shell Space</option>
                                    <option value="Bare Space">Bare Space</option>
                                </select>
                                @error('stall_space')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="square_feet" class="form-label required">Square Feet</label>
                                <input type="text" id="square_feet" wire:model="square_space" class="form-control"
                                    placeholder="Enter square feet">
                                @error('square_space')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stall_no" class="form-label required">Stall No.</label>
                                <input type="text" id="stall_no" wire:model="stall_no" class="form-control"
                                    placeholder="Enter stall number">
                                @error('stall_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" wire:click="clearError">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container">
        @include('includes.alerts')
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-4 mb-2">
                <h3 class="mt-2">List all Exhibitors</h3>
            </div>
            <div class="d-flex align-items-center gap-2 pe-3">
                <div class="col-auto">
                    <button class="btn w-10" wire:click="exportToExcel" wire:loading.attr="disabled"
                        {{ isset($exhibitors) && count($exhibitors) == 0 ? 'disabled' : '' }}>
                        @include('icons.table-export')
                        <span wire:loading wire:target="exportToExcel">Exporting...</span>
                        <span wire:loading.remove wire:target="exportToExcel">Export to Excel</span>
                    </button>
                </div>
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
                                wire:click="selectedExhibitorsId" {{ empty($event_id) ? 'disabled' : '' }}>Add</button>
                        </div>
                    @endif
                    <a href="#" class="mb-2 text-decoration-none pe-1 pt-2" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Move to Another Event"
                        wire:click="toggleEvents">@include('icons.cloud-upload')</a>
                @endif
                <span id="btn" wire:click="toggleBtn" class="mb-2 text-secondary pt-2" style="cursor: pointer"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                    <span>@include('icons.filter-search')</span>
                </span>
            </div>
        </div>
        @if ($showFilter == true)
            <div class="row pb-3">
                <div class="col-md-3">
                    <select id="products" class="form-select @error('exhibitor.products') is-invalid @enderror"
                        wire:model.live="productSearch" placeholder="Select Products">
                        <option value="">Select Products</option>
                        @foreach ($products as $productId => $productName)
                            <option value={{ $productId }}>{{ substr($productName, 0, 50) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-flat">
                        <input type="text" placeholder="Search Name, Email, Number, City..." class="form-control"
                            wire:model.live="search">
                        <span class="input-group-text">
                            <a href="#" wire:click="$set('search', '')" class="link-secondary"
                                title="Clear search" data-bs-toggle="tooltip">
                                @include('icons.close')
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-md-2 pt-1">
                    <a href="{{ route('exhibitor.summary', ['eventId' => $eventId]) }}"
                        class="btn btn-sm btn-outline-primary">Reset</a>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap table-striped datatable">
                    <thead>
                        <tr>
                            @if (!isset($eventId))
                                <th>
                                    <div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:model.live="selectAll" style="border-color:rgb(134, 132, 132);">
                                        </label>
                                    </div>
                                </th>
                            @endif
                            <th>#</th>
                            @if (isset($eventId))
                                <th>Stall No.</th>
                            @endif
                            <th>
                                Company
                                <span style="cursor:pointer;" wire:click="sortBy('exhibitor', 'name','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor:pointer;margin-left:-10px;"
                                    wire:click="sortBy('exhibitor', 'name','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>

                            <th>
                                Email
                                <span style="cursor:pointer;" wire:click="sortBy('exhibitor', 'email','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor:pointer;margin-left:-10px;"
                                    wire:click="sortBy('exhibitor', 'email','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>
                            <th style="padding-top: 12px">Phone No.</th>
                            <th>
                                Address
                                <span style="cursor:pointer;" wire:click="sortBy('address', 'city','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor:pointer;margin-left:-10px;"
                                    wire:click="sortBy('address', 'city','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>
                            <th>
                                Contact Person
                                <span style="cursor:pointer;" wire:click="sortBy('contact_person', 'name','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor:pointer;margin-left:-10px;"
                                    wire:click="sortBy('contact_person', 'name','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>
                            <th style="padding-top: 12px">Contact No.</th>
                            <th style="padding-top: 12px">Products</th>
                            <th>
                                No of Appointments
                                <span style="cursor:pointer;"
                                    wire:click="sortBy('appointments', 'appointments_count','asc')">
                                    @include('icons.arrow-narrow-up')
                                </span>
                                <span style="cursor:pointer;margin-left:-10px;"
                                    wire:click="sortBy('appointments', 'appointments_count','desc')">
                                    @include('icons.arrow-narrow-down')
                                </span>
                            </th>
                            @if (isset($eventId))
                                <th class="w-1"></th>
                            @endif
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($exhibitors) && count($exhibitors) > 0)
                            @foreach ($exhibitors as $exhibitorsIndex => $exhibitor)
                                <tr wire:key="{{ $exhibitor->id }}">
                                    @if (!isset($eventId))
                                        <td>
                                            <div>
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="selectedExhibitors" value="{{ $exhibitor->id }}"
                                                        style="border-color:rgb(134, 132, 132);">
                                                </label>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        {{ $exhibitorsIndex + $exhibitors->firstItem() }}
                                    </td>
                                    @if (isset($eventId))
                                        <td class="text-left fs-5">
                                            @if (!empty($exhibitor->eventExhibitors->where('event_id', $eventId)->first()->stall_no ?? ''))
                                                {{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->_meta['stall_space'] ?? 'NA' }}/
                                                {{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->_meta['square_space'] ?? 'NA' }}/
                                                {{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->stall_no ?? 'NA' }}
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-left small lh-base">{{ $exhibitor->name }}</td>
                                    <td class="text-left small lh-base text-wrap">{{ $exhibitor->email }}</td>
                                    <td class="text-left small lh-base text-wrap">{{ $exhibitor->mobile_number }}</td>
                                    <td class="text-left small lh-base" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-html="true"
                                        title='
                                    @if (!empty($exhibitor->address->city)) Pincode: {{ $exhibitor->address->pincode ?? '_' }}, City: {{ $exhibitor->address->city ?? '_' }}, State: {{ $exhibitor->address->state ?? '_' }},
 Country: {{ $exhibitor->address->country ?? '--' }}, Address: {{ $exhibitor->address->address ?? '--' }} @else Pincode: {{ $exhibitor->address->pincode ?? '_' }},Country: {{ $exhibitor->address->country ?? '--' }}, Address: {{ $exhibitor->address->address ?? '--' }} @endif
                                    '>
                                        {{ $exhibitor->address->city ?? $exhibitor->address->country }}

                                    </td>

                                    <td class="text-left @if (strlen($exhibitor->exhibitorContact->name ?? '_') > 25) text-wrap @endif">
                                        <strong
                                            class="small lh-base">{{ $exhibitor->exhibitorContact->salutation ?? '_' }}.{{ $exhibitor->exhibitorContact->name ?? '_' }}</strong><br>
                                        <small>{{ $exhibitor->exhibitorContact->designation ?? '_' }}</small>
                                    </td>
                                    <td class="text-left small lh-base">
                                        {{ $exhibitor->exhibitorContact->contact_number ?? '_' }}
                                    </td>
                                    <td class="fs-5 small lh-base">
                                        @if (isset($eventId))
                                            <div class="text-capitalize">
                                                @php
                                                    $productNames = collect();

                                                    foreach ($exhibitor->eventExhibitors->where('event_id', $eventId) as $eventExhibitor) {
                                                        $eventProductNames = explode(',', $eventExhibitor->getProductNames());
                                                        $filteredProducts = array_filter($eventProductNames, function ($value) {
                                                            return !is_null($value) && $value !== '';
                                                        });
                                                        $productNames = $productNames->concat($filteredProducts);
                                                    }

                                                    $productCount = $productNames->count();
                                                @endphp

                                                @if ($productCount > 0)
                                                    {{ implode(', ', $productNames->take(2)->all()) }}
                                                    @if ($productCount > 2)
                                                        <a href="#" data-bs-toggle="tooltip"
                                                            title="{{ implode(', ', $productNames->slice(2)->all()) }}"
                                                            class="fs-5">
                                                            <br>+{{ $productCount - 2 }} more
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-capitalize">
                                                @php
                                                    $overallProducts = [];
                                                @endphp
                                                @foreach ($exhibitor->exhibitorProducts as $exhibitorProduct)
                                                    @php
                                                        $product = $exhibitorProduct->product->name;
                                                        $overallProducts[] = $product;
                                                    @endphp
                                                @endforeach
                                                @if (count($overallProducts) > 2)
                                                    {{ implode(', ',collect($overallProducts)->take(2)->all()) }}
                                                    <a href="#" data-bs-toggle="tooltip"
                                                        title="{{ implode(', ',collect($overallProducts)->slice(2)->all()) }}"
                                                        class="fs-5">
                                                        <br>+{{ count($overallProducts) - 2 }} more
                                                    </a>
                                                @else
                                                    {{ implode(', ', collect($overallProducts)->all()) }}
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-capitalize small lh-base">
                                            @if (isset($eventId) && $exhibitor->appointments->where('event_id', $eventId)->count() > 0)
                                                {{ $exhibitor->appointments->where('event_id', $eventId)->count() }}
                                            @elseif(!isset($eventId) && $exhibitor->appointments->count() > 0)
                                                {{ $exhibitor->appointments->count() }}
                                            @else
                                                No Appointments
                                            @endif
                                        </div>
                                    </td>
                                    @php
                                        $previousEvents = getPreviousEvents();
                                        $isPreviousEvent = in_array($eventId, $previousEvents->pluck('id')->toArray());
                                    @endphp
                                    @if (isset($eventId) && !$isPreviousEvent)
                                        <td>
                                            <a href="#" wire:click="getExhibitorId({{ $exhibitor->id }})"
                                                title="Allocate Stall" data-toggle="tooltip" data-placement="top"
                                                data-bs-toggle="modal" data-bs-target="#stall_allocate">
                                                @include('icons.aspect-ratio')
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        @if (!$isPreviousEvent)
                                            <a href="{{ route('exhibitor.edit', ['eventId' => $eventId, 'exhibitorId' => $exhibitor->id]) }}"
                                                title="Edit" data-toggle="tooltip" data-placement="top">
                                                @include('icons.edit')
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$isPreviousEvent)
                                            <a  href="#myModal" class=" btn"
                                                title="view" data-toggle="tooltip" data-placement="top">
                                                @include('icons.view')
                                            </a>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if (isset($exhibitors) && count($exhibitors) == 0)
                            @livewire('not-found-record-row', ['colspan' => 12])
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    {{ $exhibitors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('closeModal', function() {
                $('#stall_allocate').modal('hide');

                $('#stall_no').val('');
                $('#space').val('');
                $('#square_feet').val('');

            });
        });
    </script>
@endpush
