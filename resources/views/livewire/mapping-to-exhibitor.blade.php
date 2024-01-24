@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div class="page-body">
    <div wire:ignore.self class="modal modal-blur fade" id="mappingExhibitors" tabindex="-1" role="dialog"
        aria-hidden="true" data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" id="mapping-exhibitors">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mapping To Exhibitor</h5>
                    <button type="button" class="btn-close" wire:click.prevent='closeModal'
                        aria-label="Close"></button>
                </div>
                <div class="modal-body row " id="form">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="user">Name of the Sales Person</label>
                        <input type="text" class="form-control" id="user" wire:model.live="userName" disabled>
                    </div>

                    <div class="col-md-8 mb-3" id="ts">
                        <div wire:ignore>
                            <label class="form-label" for="exhibitortom">Select Exhibitors</label>
                            <select id="exhibitortom" wire:model.live="exhibitorId" class="form-select"
                                placeholder="Choose Exhibitor" multiple autocomplete="off">

                                @if (isset($exhibitors) && count($exhibitors) > 0)
                                    @foreach ($exhibitors as $exhibitor)
                                        <option value={{ $exhibitor->id }}>{{ $exhibitor->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-danger text-decoration-none" {{-- data-bs-dismiss="modal" --}}
                        wire:click='closeModal'>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto" wire:click='mapExhibitor'>
                        <span>
                            @include('icons.plus')
                        </span>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 col-lg-12">
        @include('includes.alerts')
        <div class="card p-2">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div>
                    <h4>List of Sales Person </h4>
                </div>
                <div>
                    <span id="btn" wire:click="toggleBtn" style="cursor: pointer" class=" mb-2">
                        <span class="me-3">@include('icons.filter-search')</span>
                    </span>
                    @if ($toggleContent == true)
                        <a href ="#" class="me-2 text-danger text-decoration-none fw-bold"
                            wire:click="resetFilter"><small>Reset</small></a>
                    @endif
                </div>
            </div>
            @if ($toggleContent == true)
                <div class="card-body d-flex justify-content-between p-2 ">
                    <div class="d-flex align-items-center" style="width:28%">

                        <input type="text" class="form-control"   wire:model.live="search"
                            placeholder="Search Exhibitors, Sales Persons">
                        <span wire:click="resetFilter" class="p-2"
                            style="margin-left:-20%">@include('icons.close')</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <div class="d-flex">
                                    Sales Person
                                    <span data-bs-toggle="tooltip" title="Sort By Asc" wire:click="orderByAsc('type')"
                                        style="cursor: pointer">
                                        @include('icons.arrow-narrow-up')
                                    </span>
                                    <span data-bs-toggle="tooltip" data-bs-placement="right" title="Sort By Desc"
                                        wire:click="orderByDesc('type')" style="cursor: pointer">
                                        @include('icons.arrow-narrow-down')
                                    </span>
                                </div>
                            </th>
                            <th>
                                <div>
                                    Mapped Exhibitor
                                </div>
                            </th>
                            <th class="w-1">
                                <div>
                                    Action
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($users) && count($users) > 0)
                            @foreach ($users as $userIndex => $user)
                                <tr wire:key='item-{{ $user->id }}'>
                                    <td>
                                        {{ $userIndex + $users->firstItem() }}
                                    </td>
                                    <td>
                                        <div class="text-capitalize">
                                            {{ $user->name ?? 'User Name' }}
                                        </div>
                                    </td>
                                    <td>
                                        {{-- @dump(count($user->eventExhibitors) > 1, $user->eventExhibitors[0]) --}}
                                        @foreach ($user->eventExhibitors as $eventExhibitor)
                                            <div class="text-capitalize badge bg-yellow">
                                                <div>{{ $eventExhibitor->exhibitor->name ?? 'Exhibitor Name' }}</div>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="#" wire:click='getUserId({{ $user->id }})' id="map"
                                            title="Map Exhibitor to user" data-toggle="tooltip" data-placement="top"
                                            data-bs-toggle="modal" data-bs-target="#mappingExhibitors">
                                            @include('icons.plug-connected')
                                        </a>
                                        {{-- <a class="px-3 text-danger" href="#"
                                            wire:click='getUserId({{ $user->id }})' title="Remove Mapped Exhibitor"
                                            data-toggle="tooltip" data-placement="top" data-bs-toggle="modal"
                                            data-bs-target="#mappingExhibitors">
                                            @include('icons.plug-connected')
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @livewire('not-found-record-row', ['colspan' => 4])
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="row d-flex flex-row ">
                    @if (isset($users) && count($users) != 0)
                        <div class="col">
                            <div class="d-flex flex-row">
                                <div>
                                    <label class="p-2" for="perPage">Per Page</label>
                                </div>
                                <div>
                                    <select class="form-select" id="perPage" name="perPage" wire:model="perPage"
                                        wire:change="changePageValue($event.target.value)">
                                        <option value=10>10</option>
                                        <option value=50>50</option>
                                        <option value=100>100</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col d-flex justify-content-end">
                        @if (isset($users) && count($users) >= 0)
                            {{ $users->links() }}
                        @endif
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
            var exhibitorstomselect = new TomSelect('#exhibitortom', {
                plugins: ['dropdown_input', 'remove_button'],
                // onDelete: function(values) {
                //     console.log(values);
                //     return confirm(values.length > 1 ? "Are you sure you want unlink the  " + values
                //         .length + " exhibitors?" : "Are you sure you want unlink the exhibitor  " +
                //         values[0] + "?");
                // }
            });
            Livewire.on('setValueInTomSelect', function(exhibitorIds) {
                // console.log(exhibitorIds);
                exhibitorstomselect.setValue(exhibitorIds.id);
            });
            // console.log(exhibitorstomselect);
            Livewire.on('closeModal', function() {
                $('#mappingExhibitors').modal('hide');
            });
        });
    </script>
@endpush
