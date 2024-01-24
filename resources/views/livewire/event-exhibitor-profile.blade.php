@push('styles')
    <style>
        .table.table-striped thead th {
            background-color: unset;
        }
    </style>
@endpush
<div class="page-body">
    @livewire('appointments-modal')
    <div class="container-xl">
        @include('includes.alerts')
        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col ">
                            <a href="{{ route('visitor.find-products', ['eventId' => $exhibitorData['event_id']]) }}"
                                class="text-danger fw-bold">Back</a>
                        </div>
                        <div class="col text-center">
                            @if ($this->exhibitor['logo'])
                                <img src="{{ asset('storage/' . $this->exhibitor['logo']) }}"
                                    class="rounded-circle avatar-xl" height="120" width="120" />
                            @else
                                <span class="avatar avatar-xl">
                                    @include('icons.building')
                                </span>
                            @endif
                        </div>
                        <div class=" col text-end fw-bold">Stall No <span
                                class="badge bg-yellow text-yellow-fg ms-2">{{ $exhibitorData->stall_no }}</span></div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-md-7 table-responsive">
                            <table class="table table-vcenter card-table table-borderless">
                                <thead>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Profile Name</strong></td>
                                        <td>{{ $exhibitor->username }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company Name</strong></td>
                                        <td>{{ $exhibitor->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Business Type</strong></td>
                                        <td>{{ $exhibitor->category->name ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>{{ $exhibitor->email }}</td>
                                    </tr>
                                    <tr>
                                        <td> <strong>Phone No.</strong></td>
                                        <td>{{ $exhibitor->mobile_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address</strong></td>
                                        <td>{{ $exhibitor->address->address ?? '--' }},<br>
                                            {{ $exhibitor->address->pincode ?? '--' }},<br>
                                            {{ $exhibitor->address->city ?? '--' }},
                                            {{ $exhibitor->address->state ?? '--' }},<br>
                                            {{ $exhibitor->address->country ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Website</strong></td>
                                        <td>{{ $exhibitor->_meta['website_url'] ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Products</strong></td>
                                        <td>
                                            @foreach (explode(',', $exhibitorData->getProductNames()) as $productName)
                                                {{ $productName }}
                                                @if (!$loop->last)
                                                    {{ ',' }}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company Description</strong></td>
                                        <td>{{ $exhibitor->description ?? '--' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-5">
                            <div class="card">
                                {{-- <div class="card-header row ">
                                    <h3 class="card-title mx-auto">Contact Person Info</h3>
                                </div> --}}
                                <div class="card-body table-responsive">
                                    <table class="table table-striped">
                                        <thead class="thead">
                                            <th colspan="2">
                                                <h3 class="fw-bold card-title text-center">Contact Person Info</h3>
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Contact Person</th>
                                                <td>{{ $exhibitor->exhibitorContact->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Contact Number</th>
                                                <td>{{ $exhibitor->exhibitorContact->contact_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Designation</th>
                                                <td>{{ $exhibitor->exhibitorContact->designation }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row col-md-12 pt-4 pt-2 align-items-center ">
                                <div class="col-md-6 ">
                                    <button class="btn btn-sm btn-appointment"
                                        wire:click="addAppointment()" data-bs-toggle="modal"
                                        data-bs-target="#modal-report">
                                        @include('icons.calender-plus')
                                        <span style="margin-right:14px padding-left:10px"> Make
                                            Appointment </span>
                                    </button>
                                </div>
                                <div class="col-md-6 ">
                                    <a href="#" class="list-group-item-actions btn btn-sm"
                                        wire:click="toggleWishlist()">
                                        {{ $this->targetIdExistsInWishlist() ? 'Added Wishlist' : 'Add to Wishlist' }}
                                    </a>
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
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('closeModal', function() {
                $('#modal-report').modal('hide');
            });
        });
    </script>
@endpush
