@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div>
    <div class="container">
        <div class="page-header">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Edit Visitor Information
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cards">
            @include('includes.alerts')

            <div class="col-12">
                <form wire:submit="update" class="card" id="visitor-registration-form"
                    style="margin-top: 20px;">

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card-body">
                                    {{-- <div class="mb-3 row">
                                        <label for="event" class="col-4 form-label required">Event</label>
                                        <div class="col">
                                            <select class="form-select mb-3" wire:model.live="visitor.event_id">
                                                <option value="">Select Event</option>
                                                @if (isset($events) && count($events) > 0)
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}">
                                                            {{ $event->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('visitor.event_id')
                                                <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div> --}}



                                    {{-- <div class="d-flex align-items-center">
                                        <label class="form-label required mb-4" style="margin: 0 1px;">Name</label>
                                        <div class="input-group" style="margin-left: 98px;">
                                            <div class="col-4">
                                                <select class="form-select" wire:model="visitor.salutation"
                                                    id="salutation">
                                                    <option>Mr</option>
                                                    <option>Ms</option>
                                                    <option>Doctor</option>
                                                </select>
                                            </div>
                                            <div class="col mb-4">
                                                <input type="text" class="form-control"
                                                    wire:model.live.debounce.500ms="visitor.name" id="uname">
                                            </div>

                                        </div>
                                        @error('visitor.name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="mb-3 row">
                                        <label for="event" class="col-4 form-label required ">Name</label>

                                        <div class="col d-flex align-items-center">
                                            <select class="form-select me-1" wire:model="visitor.salutation"
                                                id="salutation" style="width: 25%;">
                                                <option value='Dr'>Dr</option>
                                                <option value='Mr'>Mr</option>
                                                <option value='Ms'>Ms</option>
                                                <option value='Mrs'>Mrs</option>
                                            </select>

                                            <input type="text" class="form-control" wire:model="visitor.name"
                                                id="name">


                                            @error('visitor.name')
                                                <span class="text-danger ">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>


                                    <div class="mb-4 row">
                                        <label for="mobile_number" class="col-4 form-label required mb-0">Mobile
                                            Number</label>
                                        <div class="col">
                                            <input type="text" class="form-control"
                                                wire:model.live="visitor.mobile_number" id="mobile_number">
                                            @error('visitor.mobile_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="country" class="col-4 col-form-label required">Designation</label>
                                        <div class="col">
                                            <input type="text" class="form-control" wire:model.live="visitor.designation"
                                                id="designation">
                                        </div>
                                        @error('visitor.designation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4 row">
                                        <label for="nature_of_business" class="col-4 form-label">Nature of
                                            Business</label>
                                        <div class="col">
                                            <select class="form-select" wire:model="visitor.category_id"
                                                id="category_id">
                                                <option value="">Select a category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        {{-- <label for="product_looking_for" class="col-4 form-label d-block">Product
                                            Looking for</label>
                                        <div class="col" id="product">
                                            <div wire:ignore>
                                                <select id="product_looking_for"
                                                    wire:model="visitor.product_looking" multiple>
                                                    <option value="">Select products</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}

                                        <!-- Reason for Visit field -->
                                        <div class="col-12">
                                            <label for="reason_for_visit" class="form-label">Reason for Visit</label>
                                            <textarea class="form-control" rows="3" wire:model="visitor.reason_for_visit"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card-body">
                                    <!-- Name field -->
                                    <div class="mb-4 row">
                                        <label for="username" class="col-4 form-label required mb-0">Profile
                                            Name</label>
                                        <div class="col">
                                            <input type="text" class="form-control" wire:model.live="visitor.username"
                                                id="uname" disabled>
                                            {{-- @error('visitor.username')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                    </div>

                                    <div class="mb-4 row">
                                        <label for="email" class="col-4 form-label required mb-0">Email</label>
                                        <div class="col">
                                            <input type="text" class="form-control" wire:model.live="visitor.email"
                                                id="email">
                                            @error('visitor.email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4 row">
                                        <label for="organization"
                                            class="col-4 form-label required mb-0">Organization</label>
                                        <div class="col">
                                            <input type="text" class="form-control"
                                                wire:model="visitor.organization" id="organization">
                                            @error('visitor.organization')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4 row">
                                        <label for="known_source" class="col-4 form-label mb-0">Known Source</label>
                                        @php
                                            $knowSources = getKnownSourceData();
                                        @endphp
                                        <div class="col">
                                            <select class="form-select" wire:model="visitor.known_source"
                                                id="known_source">
                                                <option>Select known source</option>
                                                @foreach ($knowSources as $knowSourceKey => $knowSourceLabel)
                                                    <option value="{{ $knowSourceKey }}">{{ $knowSourceLabel }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>


                                    <div class="mb-3 row">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter"
                                        wire:model.live="visitor.newsletter" style="border-color:rgb(134, 132, 132);">
                                    <span class="form-check-label">Sign up for Newsletters, Industry and Show
                                        updates</span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('visitor-registration') }}" class="text-danger me-2">Reset</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect("#product_looking_for", {
            plugins: ['dropdown_input'],
        });
    </script>
@endpush
