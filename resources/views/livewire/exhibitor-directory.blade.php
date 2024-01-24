<div>
    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')

            <h3 class="card-title">Exhibitor Directory</h3>

            <div class="row row-cards">
                <div class="col-md-6">
                    <input type="text" class="form-control mb-3" placeholder="Filter Exhibitors/Stall No./Products"
                        wire:model.live.debounce.500ms="searchTerm">
                </div>
            </div>

            <div class="accordion row row-cards" id="exhibitorAccordion">
                @foreach ($exhibitors as $exhibitor)
                    <div class="col-md-6">
                        <div class="bg-white">
                            <div class="accordion-item">
                                <div class="accordion-header p-2" id="heading{{ $exhibitor->id }}">
                                    <button class="accordion-button pb-1" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $exhibitor->id }}" aria-expanded="false"
                                        aria-controls="collapse{{ $exhibitor->id }}">
                                        <div class="d-flex flex-column">
                                            <div class="row align-items-center">

                                                <div class="col d-flex flex-column">
                                                    <span class="text-reset">{{ $exhibitor->name }}</span>
                                                    <span class="text-secondary">
                                                        Stall No:
                                                        {{ $exhibitor->eventExhibitors->first()->stall_no ?? 'NA' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                    <div class="row px-3">
                                        <div class="col-md-12">
                                            <div class="badges d-flex align-items-center flex-wrap">
                                                @foreach ($exhibitor->exhibitorProducts as $exhibitorProduct)
                                                    <span
                                                        class="badge badge-outline text-secondary fw-normal badge-pill m-1 text-wrap" style="font-size: 10px;">
                                                        {{ $exhibitorProduct->product->name  ?? ''}}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <button class="btn btn-sm"
                                                    wire:click="toggleWishlist({{ $exhibitor->id }}, {{ $eventId }})">
                                                    {{ $this->targetIdExistsInWishlist($exhibitor->id, $eventId, 'exhibitor') ? 'Added Wishlist' : 'Add to Wishlist' }}
                                                </button>
                                                <button class="btn btn-sm btn-appointment"
                                                    wire:click="addAppointment({{ $exhibitor->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#modal-report">
                                                    @include('icons.calender-plus')
                                                    <span> Make Appointment </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse{{ $exhibitor->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $exhibitor->id }}" data-bs-parent="#exhibitorAccordion">

                                    <div class="accordion-body">

                                        <div class="card-header">
                                            <div class="card-title"><b>Exhibitor Info</b></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">

                                                @include('icons.building-skyscraper')
                                                Exhibitor Name:
                                                <strong>{{ $exhibitor->name ?? 'NA' }}</strong>
                                            </div>
                                            <div class="mb-2">

                                                @include('icons.phone')
                                                Contact Number:
                                                <strong>{{ $exhibitor->mobile_number ?? 'NA' }}</strong>
                                            </div>
                                            <div class="mb-2">

                                                @include('icons.user')
                                                Contact Person Name:
                                                <strong>{{ $exhibitor->exhibitorContact->name ?? 'NA' }}</strong>
                                            </div>
                                            <div class="mb-2">

                                                @include('icons.device-mobile')
                                                Contact Person No.:
                                                <strong>{{ $exhibitor->exhibitorContact->contact_number ?? 'NA' }}</strong>
                                            </div>
                                            <div class="mb-2">

                                                @include('icons.mail')
                                                Email: <strong><span class="flag flag-country-si"></span>
                                                    {{ $exhibitor->email ?? 'NA' }}</strong>
                                            </div>

                                            <div class="mb-2">
                                                @include('icons.world')
                                                Website:
                                                <strong>{{ $exhibitor->website ?? '' }}</strong>
                                            </div>

                                        </div>
                                        @if ($exhibitor && isset($exhibitor['description']) && $exhibitor['description'])
                                            <div class="col-12">

                                                <div class="card-header">
                                                    <div class="card-title"><b>Description</b></div>
                                                </div>
                                                <div class="card-body">
                                                    <div>
                                                        <p>{{ $exhibitor->description ?? '' }}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @livewire('appointments-modal')
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
