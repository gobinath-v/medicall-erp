@push('styles')
    <style>
        .card-body-scrollable {
            max-height: 300px;
            overflow-y: auto;

        }

        .card-body-scrollable-shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush


<div>
    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')

            <div class="row row-cards mb-3">
                <div class="col-md-6">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Exhibitors</h3>
                                </div>
                                <div class="card card-body-scrollable card-body-scrollable-shadow">
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        @foreach ($exhibitorWhishlists as $wishlist)
                                            <div class="list-group-item">
                                                <div class="row align-items-center">

                                                    <div class="col text-truncate">
                                                        <a class="text-reset"
                                                            href="{{ route('eventexhibitor.profile', ['eventId' => $eventId, 'exhibitorId' => $wishlist->exhibitor->id]) }}"
                                                            target="_blank">
                                                            {{ $wishlist->exhibitor->name ?? '' }}</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button href="#" class="btn btn-sm btn-appointment"
                                                            wire:click="addAppointment({{ $wishlist->exhibitor_id }})"
                                                            data-bs-toggle="modal" data-bs-target="#modal-report">
                                                            @include('icons.calender-plus')
                                                            <span style="margin-right:14px padding-left:10px">
                                                                Make
                                                                Appointment </span>
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Products</h3>
                                </div>
                                <div class="card card-body-scrollable card-body-scrollable-shadow">
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        @foreach ($productWhishlists as $wishlist)
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span
                                                                class="text-truncate">{{ $wishlist->product->name }}</span>
                                                            {{-- <a href="{{ route('visitor.find-products', ['eventId' => $eventId]) }}"
                                                                class="btn btn-sm align-content-end custom-btn"
                                                                style="background-color: #f1a922; color: rgb(10, 10, 10);">
                                                                @include('icons.calender-plus')
                                                                <span
                                                                    style="margin-right: 14px; padding-left: 10px;">Book
                                                                    Appointment</span>
                                                            </a> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cards">
                <div class="col-md-6">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Similar Exhibitors</h3>
                                </div>
                                <div class="card card-body-scrollable card-body-scrollable-shadow">
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        @foreach ($similarExhibitors as $similarExhibitor)
                                            <div class="list-group-item">
                                                <div class="row align-items-center">

                                                    <div class="col text-truncate">
                                                        <a>  {{ $similarExhibitor->exhibitor->name }}</a>
                                                    </div>
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-between">
                                                        <button class="btn btn-sm custom-btn"
                                                            wire:click="toggleWishlist({{ $similarExhibitor->exhibitor_id }}, {{ $eventId }})"
                                                            style="padding-right: 5px; padding-left: 5px;">
                                                            {{ $this->targetIdExistsInWishlist($similarExhibitor->exhibitor_id, $eventId, 'exhibitor') ? 'Added Wishlist' : 'Add to Wishlist' }}
                                                        </button>
                                                        <button class="btn btn-sm btn-appointment"
                                                            wire:click="addAppointment({{ $similarExhibitor->exhibitor_id }})"
                                                            data-bs-toggle="modal" data-bs-target="#modal-report"
                                                            style="padding-right: 10px; padding-left: 10px;">
                                                            @include('icons.calender-plus')
                                                            <span style="margin-right: 5px; margin-left: 5px;">Make
                                                                Appointment</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Similar Products</h3>
                                </div>
                                <div class="card card-body-scrollable card-body-scrollable-shadow">
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        @foreach ($similarProducts as $product)
                                            <div class="list-group-item">
                                                <div class="row align-items-center">

                                                    <div class="col">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-truncate">{{ $product->name }}</span>
                                                            {{-- <a href="{{ route('visitor.find-products', ['eventId' => $eventId]) }}"
                                                                class="btn btn-sm align-content-end custom-btn"
                                                                style="background-color: #f1a922; color: rgb(10, 10, 10);">
                                                                @include('icons.calender-plus')
                                                                <span
                                                                    style="margin-right: 14px; padding-left: 10px;">Book
                                                                    Appointment</span>
                                                            </a> --}}
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
