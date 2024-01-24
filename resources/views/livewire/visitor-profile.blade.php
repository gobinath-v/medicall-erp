<div class="page-body">
    <div wire:ignore.self class="modal modal-blur fade" id="add_products" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Products</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <form wire:submit="updateVisitorProducts">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="mb-3" id="ts">
                                <label class="form-label required">Product Looking for</label>
                                <div wire:ignore>
                                    <select id="products"
                                        class="form-select @error('product_looking') is-invalid @enderror"
                                        wire:model.live="product_looking" placeholder="Select Products" multiple>
                                        @foreach ($productData as $productId => $productName)
                                            <option value="{{ $productId }}">
                                                {{ $productName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_looking')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container p-3">

        <div class="row">
            @include('includes.alerts')
            <div class="col-md-4">
                <div class="pb-3">

                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="mx-auto ">Update Profile Photo</h3>
                    </div>
                    <div class="card-body text-center">
                        <div>
                            <img src="{{ asset('storage/' . ($this->visitor['avatar'] ?? '')) }}"
                                class="rounded-circle avatar-xl mx-auto" height="120" width="120" />
                        </div>
                        <input type="file"class="form-control" id="updateImage" wire:model="photo" hidden />
                        <br>
                        <button class="w-25 btn mx-auto border-0 bg-default mt-3"
                            onclick="document.getElementById('updateImage').click(event.preventDefault())">
                            @include('icons.pencil')
                        </button>

                        <button type="button" wire:loading.attr="disabled" wire:target="photo"
                            class="w-25 btn btn-primary mx-auto mt-3" wire:click="update" wire:click="update"
                            {{ empty($photo) ? 'disabled' : '' }}>
                            Upload
                        </button>
                    </div>
                </div>
                {{-- <div class="card">


                    <h3 class="text-center">Update Document Info</h3>
                    <div class="mb-4">
                        <label for="mobile_number" class="form-label required mb-0">Mobile
                            Number</label>
                        <div class="col">
                            <input type="text" class="form-control" wire:model.live="visitor.mobile_number"
                                id="mobile_number">
                            @error('visitor.mobile_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4 ">
                        <label for="email" class=" form-label required mb-0">Email</label>
                        <div class="col">
                            <input type="text" class="form-control" wire:model.live="visitor.email" id="email">
                            @error('visitor.email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                </div> --}}
            </div>

            <div class="col-md-8 pt-3">

                <div class="card">

                    <div class="card-header">
                        <h3 class="mx-auto "> Profile Info</h3>

                        @if ($isDisabled)
                            <a href="#" class="text-decoration-none" wire:click="editProfile">Edit Profile</a>
                        @else
                            <button type="button" class="btn btn-sm btn-secondary me-2"
                                wire:click="backToProfile">Back</button>
                            <button type="submit" class="btn btn-sm btn-primary"
                                wire:click="visitorDetailsUpdate">Save</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <div class="d-flex align-items-center">

                                    <select class="form-select" wire:model="visitor.salutation" id="salutation"
                                        style="width: 40%" {{ $isDisabled ? 'disabled' : '' }}>
                                        <option>Mr</option>
                                        <option>Ms</option>
                                        <option>Doctor</option>
                                    </select>
                                    <input type="text" class="form-control"
                                        wire:model.live.debounce.500ms="visitor.name" id="uname"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                </div>
                                @error('visitor.name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror


                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Profile
                                        Name</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model.live="visitor.username"
                                            id="uname" disabled>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="email" class=" form-label">Email</label>

                                    <input type="text" class="form-control" wire:model.live="visitor.email"
                                        id="email" {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('visitor.email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mobile_number" class="form-label">Mobile
                                        Number</label>

                                    <input type="text" class="form-control"
                                        wire:model.live="visitor.mobile_number" id="mobile_number" disabled>


                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nature_of_business" class="form-label">Nature of Business</label>

                                    <select class="form-select" wire:model="visitor.category_id" id="category_id"
                                        {{ $isDisabled ? 'disabled' : '' }}>
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

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class=" mb-3">
                                    <label for="designation" class="form-label ">Designation</label>
                                    <div class="col">
                                        <input type="text" class="form-control"
                                            wire:model.live="visitor.designation" id="designation"
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                    </div>
                                    @error('visitor.designation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 ">
                                    <label for="organization" class=" form-label">Organization</label>
                                    <div class="col">
                                        <input type="text" class="form-control" wire:model="visitor.organization"
                                            id="organization" {{ $isDisabled ? 'disabled' : '' }}>
                                        @error('visitor.organization')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <div>
                                        <select id="country"
                                            class="form-select @error('visitor.country') is-invalid @enderror"
                                            wire:model.live="visitor.country" wire:change='clearAddressFields()'
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                            @foreach ($countries as $country)
                                                <option value={{ $country }}>{{ $country }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">
                                        {{ $visitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}
                                    </label>
                                    <input type="text" id="pincode"
                                        placeholder="Enter {{ $visitor['country'] == 'India' ? 'Pincode' : 'Zipcode' }}"
                                        class="form-control @error('visitor.pincode') is-invalid @enderror"
                                        wire:model="visitor.pincode" wire:blur='pincode()'
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('visitor.pincode')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div @if ($visitor['country'] != 'India') style="display: none;" @endif>

                                    <label for="city" class="form-label">City</label>
                                    <div>
                                        <input type="text" class="form-control" wire:model.live="visitor.city"
                                            wire:blur='pincode()' id="city" disabled>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div @if ($visitor['country'] != 'India') style="display: none;" @endif>
                                    <label for="state" class="form-label">State</label>
                                    <div>
                                        <input type="text" id="state" disabled class="form-control"
                                            wire:model="visitor.state">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label ">Address</label>
                                <textarea placeholder="Enter address" rows="3"
                                    class="form-control @error('visitor.address') is-invalid @enderror" wire:model="visitor.address"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                        </textarea>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <h3>Event Details</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Event</th>
                                        <th>Products Looking For</th>
                                        <th>Add Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($visitorData->eventVisitors as $visitorData)
                                        <tr wire:key="{{ $visitorData->id }}">
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $visitorData->event->title ?? '' }}</td>

                                            <td>
                                                @foreach (explode(',', $visitorData->getProductNames()) as $productName)
                                                    {{ $productName }}
                                                    @if (!$loop->last)
                                                        {{ ',' }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="#"
                                                    wire:click="getEventId({{ $visitorData->event_id }})"
                                                    title="Add Products" data-toggle="tooltip" data-placement="top"
                                                    data-bs-toggle="modal" data-bs-target="#add_products">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-square-check"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="1" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                        </path>
                                                        <path
                                                            d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z">
                                                        </path>
                                                        <path d="M9 12l2 2l4 -4"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            @livewire('not-found-record-row', ['colspan' => 4])
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            var productsSelect = new TomSelect('#products', {
                plugins: ['dropdown_input', 'remove_button'],
                create: true,
                createOnBlur: true,
            });

            Livewire.on('closeModal', function() {
                $('#add_products').modal('hide');
                productsSelect.clear();
            });

            Livewire.on('showProducts', function(productIds) {
                productsSelect.setValue(productIds);
            });
        });
    </script>
@endpush
