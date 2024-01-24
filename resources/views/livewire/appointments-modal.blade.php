<div wire:ignore.self class="modal modal-blur fade" id="modal-report" data-bs-backdrop="static" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ isset($appointmentId) ? 'Edit Appointment Schedule' : 'Add new appointment' }}
                </h5>
                <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    @if (isOrganizer())
                        <label class="form-label">Name of the Visitor</label>
                        <input type="text" class="form-control"
                            value="{{ $visitor->name ?? ''}}"
                            disabled>
                        <br>
                        {{-- @dump($appointmentData,$exhibitor,$appointmentId) --}}
                        <label class="form-label">Choose Exhibitor</label>
                        <select class="form-select" wire:model.live="selectedExhibitorId">
                            <option value="">Choose Exhibitor</option>
                            @foreach ($exhibitors as $key => $exhibitor)
                                <option value="{{ $exhibitor->id }}">{{ $exhibitor->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <label class="form-label">Name of the Exhibitor</label>
                        <input type="text" class="form-control"
                            value="{{ isset($appointmentId) ? $appointmentData->exhibitor->name : $exhibitor->name ?? '' }}"
                            disabled>
                    @endif
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">Schedule on</label>
                    <input type="datetime-local" class="form-control" wire:model="scheduledAt"
                        @if ($selectedEvent) min="{{ \Carbon\Carbon::parse($selectedEvent->start_date)->toDateTimeLocalString() }}"
                        max="{{ \Carbon\Carbon::parse($selectedEvent->end_date)->toDateTimeLocalString() }}" @endif>
                    @error('scheduledAt')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="mb-3 ">
                    <label class="form-label">Schedule on</label>
                    <div class="d-flex">
                        @foreach ($dateList as $date)
                            <div class="form-selectgroup">
                                <label class="form-selectgroup-item px-2">
                                    <input type="radio" wire:model.live="scheduledAt" value={{ $date['value'] }}
                                        class="form-selectgroup-input"
                                        {{ $scheduledAt == $date['value'] ? 'checked' : '' }}>
                                    <span class="form-selectgroup-label">
                                        {{ $date['display'] }}</span>
                                </label>
                            </div>
                        @endforeach
                        <label class="px-2">
                            <input type="time" wire:model.live="time" value={{ now()->format('TH:i') }}
                                class="form-control" min="10:00" max="18:00">
                        </label>
                    </div>
                    @error('scheduledAt')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- @if (isset($appointmentId))
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select text-capitalize" wire:model.live="status">
                            <option value="rescheduled">Reschedule</option>
                        </select>
                    </div>
                @endif --}}
                <div class="mb-3">
                    <label class="form-label">Purpose of Meeting</label>
                    <textarea class="form-control" rows="3" wire:model.defer="notes"
                        {{ auth()->guard('exhibitor')->check()? 'disabled': '' }}></textarea>
                    @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-danger text-decoration-none" wire:click="closeModal">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary ms-auto"
                    wire:click={{ isset($appointmentId) ? 'update' : 'saveAppointment' }}>
                    <span>
                        @include('icons.plus')
                    </span>
                    {{ isset($appointmentId) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </div>
</div>
