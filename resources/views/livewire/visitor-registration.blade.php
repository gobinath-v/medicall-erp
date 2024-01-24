@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div class="page-body">
    <div class="container">

        <div class="row row-cards">
            <div class="col-12">
                @include('includes.alerts')
                <form wire:submit="create" class="card" id="visitor-registration-form">
                    <div class="card-header">
                        <h3 class="card-title mx-auto">Visitor Registration</h3>
                        @if (auth()->guest())
                            <a href="{{ route('login') }}" class="text-primary me-2">Login</a>
                        @endif
                    </div>


                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="mb-3 row">
                                    <label for="event" class="col-4 form-label required ">Event</label>
                                    <div class="col">
                                        <select class="form-select mb-2" wire:model.defer="visitor.event_id"
                                            {{ isset($eventId) ? 'disabled' : '' }}>
                                            <option value="">Select Event</option>
                                            @foreach ($events as $eventID => $eventTitle)
                                                <option value="{{ $eventID }}">{{ $eventTitle }}</option>
                                            @endforeach
                                        </select>
                                        @error('visitor.event_id')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-4">
                                        <label for="event" class="form-label required ">Name</label>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-md-3 pe-0">
                                                <select class="form-select" wire:model="visitor.salutation"
                                                    id="salutation">
                                                    <option value='Dr'>Dr</option>
                                                    <option value='Mr'>Mr</option>
                                                    <option value='Ms'>Ms</option>
                                                    <option value='Mrs'>Mrs</option>
                                                </select>
                                            </div>
                                            <div class="col-md-9 ps-0">
                                                <input type="text" class="form-control"
                                                    wire:model="visitor.name" id="name"
                                                    wire:change="getProfileName">
                                                @error('visitor.name')
                                                    <span class="text-danger ">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <div class="mb-3 row">
                                    <label for="mobile_number" class="col-4 form-label required mb-0">Mobile
                                        Number</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitor.mobile_number"
                                            id="mobile_number">
                                        @error('visitor.mobile_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="designation" class="col-4 form-label required">Designation</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitor.designation"
                                            id="designation">
                                        @error('visitor.designation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="natureofbussiness" class="col-4 form-label required">Nature of
                                        Business</label>
                                    <div class="col">
                                        <select class="form-select" wire:model="visitor.category_id"
                                            id="category_id">
                                            <option value="">Select a category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('visitor.category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-2 row">
                                    <label for="product_looking_for" class="col-4 form-label d-block ">Product
                                        Looking for</label>
                                    <div class="col" id="product">
                                        <div wire:ignore>
                                            <select id="product_looking_for" wire:model="visitor.product_looking"
                                                multiple>
                                                <option value="">Select products</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Name field -->
                                <div class="mb-3 row">
                                    <label for="profile_name" class="col-4 form-label required mb-0">Profile
                                        Name</label>
                                    <div class="col mb-3">
                                        <input type="text" class="form-control" wire:model="visitor.username"
                                            id="username" wire:input="checkUserName" pattern="^\S+$">

                                        @if (!$username_exists && $visitor['username'] !== '')
                                            <div class="text-success">Username is available</div>
                                        @endif
                                        @error('visitor.username')
                                            <span class="text-danger ">{{ $message }}</span>
                                            @if ($visitor['name'] !== '')
                                                <p class="text-success cursor-pointer" wire:click="setSuggestedValue">
                                                    Suggested:
                                                    {{ $suggestedValue }}</p>
                                            @endif
                                        @enderror
                                    </div>

                                </div>

                                <div class="mb-3 row">
                                    <label for="email" class="col-4 form-label required mb-0">Email</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitor.email"
                                            id="email">
                                        @error('visitor.email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="organization"
                                        class="col-4 form-label required mb-0">Organization</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitor.organization"
                                            id="organization">
                                        @error('visitor.organization')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
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
                                        @error('visitor.known_source')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mb-3 row">
                                    <!-- Reason for Visit field -->
                                    <div class="col-12">
                                        <label for="reason_for_visit" class="form-label">Reason for Visit</label>
                                        <textarea class="form-control" rows="3" wire:model.live="visitor.reason_for_visit"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label for="country" class="col-4 form-label">Country</label>
                                <div>
                                    <select class="form-control" wire:model.live="visitoraddress.country"
                                        wire:change="clearAddressFields" id="country">
                                        @foreach ($countries as $country)
                                            <option value={{ $country }}>{{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="pincode" class="col-4 form-label required">
                                    {{ $visitoraddress['country'] == 'India' ? 'Pincode' : 'Zipcode' }}</label>
                                <div>
                                    <input type="text" class="form-control" wire:model.live="visitoraddress.pincode"
                                        wire:blur='pincode()' id="pincode">
                                    @error('visitoraddress.pincode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if ($visitoraddress['country'] === 'India')
                                <div class="col-md-3">
                                    <label for="city" class="col-4 form-label">City</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitoraddress.city"
                                            id="city">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="state" class="col-4 form-label">State</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitoraddress.state"
                                            id="state">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row mt-2">

                            <div class="col-12">
                                <label for="address" class="col-4 form-label required d-block">Address</label>
                                <div class="col">
                                    <textarea class="form-control" rows="3" wire:model.live="visitoraddress.address"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-2">
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
        var productSelect = new TomSelect('#product_looking_for', {
            plugins: {
                remove_button: {
                    title: 'Remove this item',
                }
            },
            persist: false,
            create: false,
        });
    </script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            var usernameInput = document.getElementById('username');

            usernameInput.addEventListener('focus', function() {
                @this.checkUserName();
            });
        });
    </script>
@endpush
