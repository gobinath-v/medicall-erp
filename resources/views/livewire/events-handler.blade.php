@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div>
    <h4 class="text">{{ isset($eventId) ? 'Edit Event' : 'New Event' }}</h4>
    <div class="card">
        <form id='eventForm' wire:submit={{ isset($eventId) ? 'update' : 'create' }}>
            <div class="card-body">
                <div class="row row-cards">


                    @if (isset($eventId))
                        <div style="margin-left: 35%">
                            @php
                                $eventImagePath = $this->event['_meta']['thumbnail'] ?? 'thumbnail/2023/11/medicall-logo-min.png';
                            @endphp
                            <img src="{{ asset('storage/' . $eventImagePath) }}" class="rounded-circle avatar-xl"
                                height="70" width="70" />
                        </div>
                        <input type="file"class="form-control" id="updateImage" wire:model="photo" hidden />
                        <br>
                        <button class="w-25 btn mx-auto border-0 bg-default mt-3"
                            onclick="document.getElementById('updateImage').click(event.preventDefault())">
                            @include('icons.edit')
                        </button>

                        {{-- <div style="margin-left: 35%">
                            @php
                                $eventLayoutPath = $this->event['_meta']['layout'] ?? 'thumbnail/2023/11/medicall-logo-min.png';
                            @endphp
                            <img src="{{ asset('storage/' . $eventLayoutPath) }}" class="rounded-circle avatar-xl"
                                height="70" width="70" />
                        </div> --}}
                        <div class="col-md-12">
                            <input type="file"class="form-control" id="updateFile" wire:model="hallLayout"
                                hidden />
                            <br>
                            <div class="d-flex justify-content-between">
                                <span class="mt-2">Change Hall Layout</span>
                                <button class=" btn border-0 bg-success-lt"
                                    onclick="document.getElementById('updateFile').click(event.preventDefault())">
                                    @include('icons.edit')
                                    Change
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="avatar-edit ">
                                    <label class="form-label" for="imageUpload">Image Updload</label>
                                    <input type='file' class="form-control" wire:model="photo"
                                        id="imageUpload" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="avatar-edit ">
                                    <label class="form-label" for="fileUpload">Hall Layout</label>
                                    <input type='file' class="form-control" wire:model="hallLayout"
                                        id="fileUpload" />
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label required" for="title">Title</label>
                        <input id="title" type="text" @class([
                            'form-control',
                            'is-invalid' => $errors->has('event.title') ? true : false,
                        ]) wire:model="event.title"
                            placeholder="Event Title">
                        @error('event.title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 ">
                        <div class="mb-3">
                            <label class="form-label required" for="startDate">Start Date</label>
                            <input id="startDate" type="date" @class([
                                'form-control',
                                'is-invalid' => $errors->has('event.startDate') ? true : false,
                            ])
                                wire:model="event.startDate" placeholder="dd-mm-yyyy">
                            @error('event.startDate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="mb-3">
                            <label class="form-label required" for="endDate">End Date</label>
                            <input id="endDate" type="date" @class([
                                'form-control',
                                'is-invalid' => $errors->has('event.endDate') ? true : false,
                            ])
                                wire:model="event.endDate" placeholder="dd-mm-yyyy">
                            @error('event.endDate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3" id="ts">
                            <div wire:ignore>
                                <label class="form-label required tomselect" for="country">Country</label>
                                <select id="country" type="select" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('event.country') ? true : false,
                                ])
                                    wire:model.live="event.country" wire:click.prevent="pincode" autocomplete="off">
                                    <option> </option>
                                    @foreach ($countries as $country)
                                        <option value={{ $country }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('event.country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label required"
                                for="pincode">{{ $event['country'] == 'India' ? 'Pin Code' : 'Zip Code' }}</label>
                            <input id="pincode" type="text" @class([
                                'form-control',
                                'is-invalid' => $errors->has('event.pincode') ? true : false,
                            ])
                                wire:model="event.pincode" wire:blur="pincode"
                                placeholder={{ $event['country'] == 'India' ? 'Postal Code' : 'Zip Code' }}>
                            @error('event.pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($event['country'] == 'India')
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="city">City</label>
                                <input id="city" type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('event.city') ? true : false,
                                ])
                                    wire:model.live="event.city" placeholder="City" disabled>
                                @error('event.city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="state">State</label>
                                <input id="state" type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('event.state') ? true : false,
                                ])
                                    wire:model.live="event.state" placeholder="State" disabled>
                                @error('event.state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label required" for ="address">Address</label>
                            <textarea id="address" @class([
                                'form-control',
                                'is-invalid' => $errors->has('event.address') ? true : false,
                            ])wire:model="event.address" placeholder="Event Address"
                                autocomplete="off"></textarea>
                            @error('event.address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                @if ($eventId)
                    <a href={{ route('events') }} class="text-danger me-2"> Cancel </a>
                @else
                    <a href=# wire:click.prevent ="resetFields" class="text-danger me-2"> Reset </a>
                @endif
                <button class="btn btn-primary">{{ isset($eventId) ? 'Update Event' : 'Create Event' }}</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            var countries = new TomSelect('#country', {
                plugins: ['dropdown_input'],
            });
        });
    </script>
@endpush
