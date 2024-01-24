@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        .product__searcher .ts-dropdown {
            z-index: 999999 !important;
        }

        .star-icon:hover {
            fill: gold;
            cursor: pointer;
        }

        .custom-btn {
            width: 130px;

        }
    </style>
@endpush
<div class="container pt-3">
    @include('includes.alerts')
    <div class="row">
        <h3 style="text-align: center;">Find Products/Exhibitors</h3>
    </div>

    <div class="row g-2 justify-content-center">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search Products/Exhibitors" wire:model.live="search">
        </div>

    </div>
    <div class="row g-2 mt-3">

        <div class="col-md-12">
            <div class=" row row-cards">
                @if (count($products) === 0 && count($exhibitors) === 0)
                    <div class="container-xl d-flex flex-column justify-content-center">
                        <div class="empty">
                            <div class="empty-img"><img src="{{ asset('images/not-found.png') }}" height="158"
                                    alt="Not Found"></div>

                            <p class="empty-title">No products or exhibitors found.</p>
                            <p class="empty-subtitle text-secondary">
                                Start exploring by entering a product or exhibitor name above.
                            </p>

                        </div>
                    </div>
                @else
                    @if (count($products) > 0)
                        <div class="col-md-6 mb-5">
                            <h4>Products</h4>
                            <div class="card">
                                <ul class="list-group list-group-flush">
                                    @foreach ($products as $product)
                                        <li class="list-group-item">
                                            <div class="row align-items-center">
                                                {{-- <div class="col-auto">
                                                    <a href="#">
                                                        <span class="avatar {{ getRandomBackgroundColor() }}">
                                                            {{ getNameFirstChars($product->name ?? '') }}
                                                        </span>
                                                    </a>
                                                </div> --}}
                                                <div class="col">
                                                    <span class="text-reset d-block">{{ $product->name ?? '' }}</span>
                                                </div>

                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions btn btn-sm"
                                                        wire:click="toggleWishlist({{ $product->id }}, {{ $eventId }},'product')">
                                                        {{ $this->targetIdExistsInWishlist($product->id, $eventId, 'product') ? 'Added Wishlist' : 'Add to Wishlist' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    @endif

                    @if (count($exhibitors) > 0)
                        <div class="col-md-6 mb-4">
                            <h4>Exhibitors</h4>
                            @foreach ($exhibitors as $exhibitor)
                                <div class="card mb-1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h3 class="mb-1">
                                                    <a href="{{ route('eventexhibitor.profile', ['eventId' => $eventId, 'exhibitorId' => $exhibitor->id]) }}"
                                                        target="_blank">{{ $exhibitor->name ?? '' }}</a>
                                                </h3>

                                                <div class="list">
                                                    @include('icons.device-landline-phone')
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-original-title="Company contact number">
                                                        {{ $exhibitor->mobile_number ?? '' }}
                                                    </span>
                                                </div>
                                                <div class="list">
                                                    @include('icons.mail')
                                                    {{ $exhibitor->email ?? '' }}
                                                </div>
                                                <div class="list d-flex align-items-center">
                                                    @include('icons.map-pin')
                                                    <span class="d-flex align-items-center ms-1">
                                                        {{ $exhibitor->address->city ?? '' }},
                                                        {{ $exhibitor->address->country ?? '' }}
                                                    </span>
                                                </div>
                                                <div class="list">
                                                    @include('icons.category')
                                                    @foreach ($exhibitor->eventExhibitors->where('event_id', $this->eventId) as $eventExhibitor)
                                                        Stall No.: {{ $eventExhibitor->stall_no ?? 'NA' }}
                                                    @endforeach
                                                </div>
                                                <div class="col-md-auto">
                                                    <div class="mt-3 badges">
                                                        @foreach ($exhibitor->eventExhibitors as $eventExhibitor)
                                                            @if (isset($eventExhibitor->product) && isset($eventExhibitor->product->name))
                                                                <a href="#"
                                                                    class="badge badge-outline text-secondary fw-normal badge-pill m-1">{{ $eventExhibitor->product->name ?? '' }}</a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 d-flex flex-column align-items-end ">
                                                <div class="mt-auto mb-5 text-align: center">
                                                    <button class="btn btn-appointment btn-sm "
                                                        wire:click="addAppointment({{ $exhibitor->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modal-report">
                                                        @include('icons.calender-plus')
                                                        <span style="margin-right:14px padding-left:10px">
                                                            Make
                                                            Appointment </span>
                                                    </button>
                                                </div>
                                                <div class="mt-auto">
                                                    <button class="btn btn-sm custom-btn"
                                                        wire:click="toggleWishlist({{ $exhibitor->id }}, {{ $eventId }})">
                                                        {{ $this->targetIdExistsInWishlist($exhibitor->id, $eventId, 'exhibitor') ? 'Added Wishlist' : 'Add to Wishlist' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @livewire('appointments-modal')
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
