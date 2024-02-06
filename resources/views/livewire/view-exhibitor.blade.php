<div>
    <div class="container p-4">
        <div class="row">
            @include('includes.alerts')
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mx-auto">Company Logo</h3>
                    </div>
                    <div class="card-body text-center">
                        <div>
                            <img src="{{ asset('storage/' . ($exhibitor['avatar'] ?? '')) }}"
                                class="rounded-circle avatar-xl mx-auto" height="120" width="120" />
                        </div>
                        <input type="file" class="form-control" id="updateImage" wire:model="photo" hidden />
                        <br>
                        <button class="w-25 btn mx-auto border-0 bg-default mt-3"
                            onclick="document.getElementById('updateImage').click(event.preventDefault())">
                            @include('icons.pencil')
                        </button>
                        <button type="button" wire:loading.attr="disabled" wire:target="photo"
                            class="w-25 btn btn-primary mx-auto mt-3" wire:click="update"
                            {{ empty($photo) ? 'disabled' : '' }}>
                            Upload
                        </button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mx-auto">Contact Person Info</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-bold">Contact Person
                                    Name</label>
                                <div class="d-flex">
                                    <div class="mb-3 col-md-4" style="padding-right: 4px">
                                        <select class="form-select @error('exhibitor.salutation') is-invalid @enderror"
                                            wire:model="exhibitor.salutation" {{ $isDisabled ? 'disabled' : '' }}>
                                            <option value="Mr" selected>Mr</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                        @error('exhibitor.salutation')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-8">
                                        <input type="text" placeholder="Enter your name"
                                            class="form-control @error('exhibitor.name') is-invalid @enderror"
                                            wire:model="exhibitor.name" {{ $isDisabled ? 'disabled' : '' }}>
                                        @error('exhibitor.name')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="mobileNumber" class="form-label fw-bold">Contact
                                    No.</label>
                                <input type="text" placeholder="Enter your contact number"
                                    class="form-control @error('exhibitor.contact_number') is-invalid @enderror"
                                    wire:model="exhibitor.contact_number" {{ $isDisabled ? 'disabled' : '' }}>
                                @error('exhibitor.contact_number')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="designation" class="form-label fw-bold">Designation</label>
                                <input type="text" placeholder="Enter your designation"
                                    class="form-control @error('exhibitor.designation') is-invalid @enderror"
                                    wire:model="exhibitor.designation" {{ $isDisabled ? 'disabled' : '' }}>
                                @error('exhibitor.designation')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mx-auto">Organization Info</h3>
                        @if ($isDisabled)
                            <a href="#" class="text-decoration-none" wire:click="editProfile">Edit Profile</a>
                        @else
                            <button type="button" class="btn btn-sm btn-secondary me-2"
                                wire:click="backToProfile">Back</button>
                            <button type="submit" class="btn btn-sm btn-primary"
                                wire:click="updateExhibitorDetails">Save</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-bold">Profile Name</label>
                                    <input type="text" placeholder="Enter company profile name" id="username"
                                        class="form-control @error('exhibitor.username') is-invalid @enderror"
                                        wire:model="exhibitor.username" wire:input="checkUserName" disabled>
                                    @error('exhibitor.username')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="companyName" class="form-label fw-bold">Company
                                        Name</label>
                                    <input type="text" placeholder="Enter company name"
                                        class="form-control @error('exhibitor.company_name') is-invalid @enderror"
                                        wire:model="exhibitor.company_name" {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('exhibitor.company_name')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="categoryId" class="form-label fw-bold">Business
                                    Type</label>
                                <select class="form-select @error('exhibitor.category_id') is-invalid @enderror"
                                    wire:model="exhibitor.category_id" {{ $isDisabled ? 'disabled' : '' }}>
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
                            <div class="col-md-7 mb-3" id="ts1">
                                <label class="form-label fw-bold">Products</label>
                                <div wire:ignore>
                                    <select id="products"
                                        class="form-select @error('exhibitor.products') is-invalid @enderror"
                                        wire:model="exhibitor.products" placeholder="Select Products" multiple
                                        {{ $isDisabled ? 'disabled' : '' }}>
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

                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" placeholder="Enter email"
                                        class="form-control @error('exhibitor.email') is-invalid @enderror"
                                        wire:model="exhibitor.email" {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('exhibitor.email')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="contactNumber" class="form-label fw-bold">Phone
                                        No.</label>
                                    <input type="text" placeholder="Enter phone no. "
                                        class="form-control @error('exhibitor.mobile_number') is-invalid @enderror"
                                        wire:model="exhibitor.mobile_number" disabled>
                                    @error('exhibitor.mobile_number')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="companyName" class="form-label fw-bold">Website
                                        URL</label>
                                    <input type="text" placeholder="Enter website url" class="form-control"
                                        wire:model="exhibitor.website_url" {{ $isDisabled ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="country" class="form-label fw-bold">Country</label>
                                    <div wire:ignore id="ts2">
                                        <select id="country"
                                            class="form-select @error('exhibitor.country') is-invalid @enderror"
                                            wire:model.live="exhibitor.country" wire:change='clearLocationFields()'
                                            {{ $isDisabled ? 'disabled' : '' }}>
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
                                    <label for="pincode" class="form-label fw-bold">
                                        {{ $exhibitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}
                                    </label>
                                    <input type="text" id="pincode"
                                        placeholder="Enter {{ $exhibitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}"
                                        class="form-control @error('exhibitor.pincode') is-invalid @enderror"
                                        wire:model="exhibitor.pincode" wire:blur='pincode()'
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('exhibitor.pincode')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div @if ($exhibitor['country'] != 'India') style="display: none;" @endif>
                                    <label for="city" class="form-label fw-bold">City</label>
                                    <div>
                                        <input type="text" id="city" disabled
                                            class="form-control @error('exhibitor.city') is-invalid @enderror"
                                            wire:model="exhibitor.city">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div @if ($exhibitor['country'] != 'India') style="display: none;" @endif>
                                    <label for="state" class="form-label fw-bold">State</label>
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
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea placeholder="Enter address" rows="3"
                                    class="form-control @error('exhibitor.address') is-invalid @enderror" wire:model="exhibitor.address"
                                    {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                @error('exhibitor.address')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Description</label>
                                <textarea placeholder="Enter Description" rows="3" class="form-control" wire:model="exhibitor.description"
                                    {{ $isDisabled ? 'disabled' : '' }}></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
