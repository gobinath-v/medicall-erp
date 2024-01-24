<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorsExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $visitors;
    protected $eventId;

    public function __construct($eventId, Collection $visitors)
    {
        $this->eventId = $eventId;
        $this->visitors = $visitors;
    }

    public function collection()
    {
        $index = 1;
        return $this->visitors->map(function ($visitor) use (&$index) {
            $participatedEvents = $visitor->eventVisitors ?? [];

            $productNames = '';
            foreach ($participatedEvents as $participatedEvent) {
                $productNames .= $participatedEvent->getProductNames();
            }

            $numberOfAppointments = $this->eventId ? $visitor->appointments->where('event_id', $this->eventId)->count() : $visitor->appointments->count();
            $numberOfAppointmentsCount = ($numberOfAppointments > 0) ? $numberOfAppointments : 'No Appointment';
            return [
                'Id' => $index++,
                'Name' => $visitor->name,
                'Mobile Number' => $visitor->mobile_number,
                'Email' => $visitor->email,
                'Nature of Business' => $visitor->category->name ?? '',
                'Organization' => $visitor->organization,
                'Designation' => $visitor->designation,
                'Reason for Visit' => $visitor->reason_for_visit,
                'Product Looking for' => $productNames,
                'No of Appointments' => $numberOfAppointmentsCount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'Name',
            'Mobile Number',
            'Email',
            'Nature of Business',
            'Organization',
            'Designation',
            'Reason for Visit',
            'Product Looking for',
            'No of Appointments',
        ];
    }
}
