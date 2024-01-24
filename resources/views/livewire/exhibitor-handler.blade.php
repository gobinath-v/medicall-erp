@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div class="container p-4">
    @include('includes.alerts')
    <form wire:submit="save">
        <div class="row">
            <div class="col-lg-12">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mx-auto">Exhibitor Registration</h3>
                                @if (auth()->guest())
                                    <a href="{{ route('login') }}" class="text-primary me-2">Login</a>
                                @endif
                            </div>
                            <div class="card-body mt-2">
                                <div class="row row-cards">
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <label for="event" class="form-label required">Event</label>
                                            <select
                                                class="form-select @error('exhibitor.event_id') is-invalid @enderror"
                                                wire:model.defer="exhibitor.event_id"
                                                {{ isset($eventId) ? 'disabled' : '' }}>
                                                <option value="">Select Event</option>
                                                @foreach ($events as $eventID => $eventTitle)
                                                    <option value="{{ $eventID }}">{{ $eventTitle }}</option>
                                                @endforeach
                                            </select>
                                            @error('exhibitor.event_id')
                                                <div class="error text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label required">Contact Person
                                                Name</label>
                                            <div class="input-group">
                                                <div class="mb-3 col-md-3" style="padding-right: 4px">
                                                    <select
                                                        class="form-select @error('exhibitor.salutation') is-invalid @enderror"
                                                        wire:model="exhibitor.salutation">
                                                        <option value="Dr">Dr</option>
                                                        <option value="Mr" selected>Mr</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Mrs">Mrs</option>
                                                    </select>
                                                    @error('exhibitor.salutation')
                                                        <div class="error text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-9">
                                                    <input type="text" placeholder="Enter your name"
                                                        class="form-control @error('exhibitor.name') is-invalid @enderror"
                                                        wire:model="exhibitor.name">
                                                    @error('exhibitor.name')
                                                        <div class="error text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="mobileNumber" class="form-label required">Contact
                                                    No.</label>
                                                <input type="text" placeholder="Enter your contact number"
                                                    class="form-control @error('exhibitor.contact_number') is-invalid @enderror"
                                                    wire:model="exhibitor.contact_number">
                                                @error('exhibitor.contact_number')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label required">Designation</label>
                                                <input type="text" placeholder="Enter your designation"
                                                    class="form-control @error('exhibitor.designation') is-invalid @enderror"
                                                    wire:model="exhibitor.designation">
                                                @error('exhibitor.designation')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="companyName" class="form-label required">Company
                                                    Name</label>
                                                <input type="text" placeholder="Enter company name"
                                                    class="form-control @error('exhibitor.company_name') is-invalid @enderror"
                                                    wire:model="exhibitor.company_name"
                                                    wire:change="getCompanyName">
                                                @error('exhibitor.company_name')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="categoryId" class="form-label required">Business
                                                    Type</label>
                                                <select
                                                    class="form-select @error('exhibitor.category_id') is-invalid @enderror"
                                                    wire:model="exhibitor.category_id">
                                                    <option value="">Select Business Type</option>
                                                    @foreach ($categories as $category)
                                                        <option value={{ $category->id }}>{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('exhibitor.category_id')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label required">Profile Name</label>
                                                <input type="text" placeholder="Enter company profile name"
                                                    id="username"
                                                    class="form-control @error('exhibitor.username') is-invalid @enderror"
                                                    wire:model="exhibitor.username" wire:input="checkUserName">

                                                @if (!$username_exists && $exhibitor['username'] !== '')
                                                    <div class="text-success">Username is available</div>
                                                @endif

                                                @error('exhibitor.username')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                    @if ($exhibitor['company_name'] !== '')
                                                        <p class="text-success cursor-pointer"
                                                            wire:click="setSuggestedValue">Suggested:
                                                            {{ $suggestedValue }}</p>
                                                    @endif
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="email" class="form-label required">Email</label>
                                                <input type="email" placeholder="Enter email"
                                                    class="form-control @error('exhibitor.email') is-invalid @enderror"
                                                    wire:model="exhibitor.email">
                                                @error('exhibitor.email')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="contactNumber" class="form-label required">Phone
                                                    No.</label>
                                                <input type="text" placeholder="Enter company phone number."
                                                    class="form-control @error('exhibitor.mobile_number') is-invalid @enderror"
                                                    wire:model="exhibitor.mobile_number">
                                                @error('exhibitor.mobile_number')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3" id="ts">
                                                <label class="form-label required">Products</label>
                                                <div wire:ignore>
                                                    <select id="products"
                                                        class="form-select @error('exhibitor.products') is-invalid @enderror"
                                                        wire:model="exhibitor.products"
                                                        placeholder="Select Products" multiple>
                                                        @foreach ($products as $productId => $productName)
                                                            <option value={{ $productId }}>{{ $productName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('exhibitor.products')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="knownSource" class="form-label required">How did you come
                                                    to
                                                    know</label>
                                                @php
                                                    $knowSources = getKnownSourceData();
                                                @endphp
                                                <select
                                                    class="form-select @error('exhibitor.known_source') is-invalid @enderror"
                                                    wire:model="exhibitor.known_source">
                                                    <option>Select Known Source</option>
                                                    @foreach ($knowSources as $knowSourceKey => $knowSourceLabel)
                                                        <option value="{{ $knowSourceKey }}">{{ $knowSourceLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('exhibitor.known_source')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="country" class="form-label required">Country</label>
                                                <div wire:ignore id="ts1">
                                                    <select id="country"
                                                        class="form-select @error('exhibitor.country') is-invalid @enderror"
                                                        wire:model.live="exhibitor.country"
                                                        wire:change='clearLocationFields()'>
                                                        @foreach ($countries as $country)
                                                            <option value={{ $country }}>{{ $country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('exhibitor.country')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="pincode" class="form-label required">
                                                    {{ $exhibitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}
                                                </label>
                                                <input type="text" id="pincode"
                                                    placeholder="Enter {{ $exhibitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}"
                                                    class="form-control @error('exhibitor.pincode') is-invalid @enderror"
                                                    wire:model="exhibitor.pincode" wire:blur='pincode()'>
                                                @error('exhibitor.pincode')
                                                    <div class="error text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div @if ($exhibitor['country'] != 'India') style="display: none;" @endif>
                                                <label for="city" class="form-label">City</label>
                                                <div>
                                                    <input type="text" id="city" disabled
                                                        class="form-control @error('exhibitor.city') is-invalid @enderror"
                                                        wire:model="exhibitor.city">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div @if ($exhibitor['country'] != 'India') style="display: none;" @endif>
                                                <label for="state" class="form-label">State</label>
                                                <div>
                                                    <input type="text" id="state" disabled
                                                        class="form-control @error('exhibitor.state') is-invalid @enderror"
                                                        wire:model="exhibitor.state">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label required">Address</label>
                                            <textarea placeholder="Enter address" rows="3"
                                                class="form-control @error('exhibitor.address') is-invalid @enderror" wire:model="exhibitor.address"></textarea>
                                            @error('exhibitor.address')
                                                <div class="error text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="exhibitor.newsletter"
                                                    style="border-color:rgb(134, 132, 132);">
                                                <span class="form-check-label"> Sign up for Newsletters, Industry
                                                    and Show updates</span>
                                            </label>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="card-footer text-end mt-3">
                                <a href="#" wire:click="resetFields()" class="text-danger me-2">Reset</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </form>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            var countries = new TomSelect('#country', {
                plugins: ['dropdown_input'],
            });

            var products = new TomSelect('#products', {
                plugins: ['dropdown_input', 'remove_button'],
                create: true,
                createOnBlur: true,
            });
            var usernameInput = document.getElementById('username');

            usernameInput.addEventListener('focus', function() {
                @this.checkUserName();
            });
        });
    </script>
@endpush
