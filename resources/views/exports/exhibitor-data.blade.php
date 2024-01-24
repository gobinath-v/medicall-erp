<table class="table">
    <thead>
        <tr>
            <th>#</th>
            @if ($eventId)
                <th>Stall No.</th>
                <th>Stall Space</th>
                <th>Square Feet</th>
            @endif
            <th>Company Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Pincode</th>
            <th>City</th>
            <th>State</th>
            <th>Country</th>
            <th>Address</th>
            <th>Salutation</th>
            <th>Contact Person</th>
            <th>Designation</th>
            <th>Contact Person No</th>
            <th>Products</th>
            <th>No Of Appointments</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($exhibitors as $exhibitorsIndex => $exhibitor)
            <tr wire:key="{{ $exhibitor->id }}">
                <td>{{ $exhibitorsIndex + 1 }}</td>
                @if ($eventId)
                    <td>{{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->stall_no ?? 'NA' }}</td>
                    <td>{{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->_meta['stall_space'] ?? 'NA' }}
                    </td>
                    <td>{{ $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->_meta['square_space'] ?? 'NA' }}
                    </td>
                @endif
                <td>{{ $exhibitor->name }}</td>
                <td>{{ $exhibitor->email }}</td>
                <td>{{ $exhibitor->mobile_number }}</td>
                <td>{{ $exhibitor->address->pincode ?? '_' }}</td>
                <td>{{ $exhibitor->address->city ?? '_' }}</td>
                <td>{{ $exhibitor->address->state ?? '_' }}</td>
                <td>{{ $exhibitor->address->country ?? '_' }}</td>
                <td>{{ $exhibitor->address->address ?? '_' }}</td>
                <td>{{ $exhibitor->exhibitorContact->salutation }}</td>
                <td>{{ $exhibitor->exhibitorContact->name ?? '_' }}</td>
                <td>{{ $exhibitor->exhibitorContact->designation }}</td>
                <td>{{ $exhibitor->exhibitorContact->contact_number ?? '_' }}</td>
                <td>
                    @if ($eventId)
                        @foreach (explode(
        ',',
        $exhibitor->eventExhibitors->where('event_id', $eventId)->first()->getProductNames(),
    ) as $productName)
                            {{ $productName }}
                            @if (!$loop->last)
                                {{ ',' }}
                            @endif
                        @endforeach
                    @else
                        @foreach ($exhibitor->exhibitorProducts as $exhibitorProduct)
                            {{ $exhibitorProduct->product->name }},
                        @endforeach
                    @endif
                </td>
                <td>
                    @if ($eventId && $exhibitor->appointments->where('event_id', $eventId)->count() > 0)
                        {{ $exhibitor->appointments->where('event_id', $eventId)->count() }}
                    @elseif(!$eventId && $exhibitor->appointments->count() > 0)
                        {{ $exhibitor->appointments->count() }}
                    @else
                        No Appointments
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
