<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AppointmentExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    public $eventId;

    protected $selectedRecordIds,$orderByName = 'scheduled_at', $orderBy ='asc';

    private $serialCount = 1;

    public function mount()
    {
        $this->eventId = request('eventId');
    }

    public function __construct($selectedIds, $sortName, $sortBy)
    {

        $this->selectedRecordIds = $selectedIds;
        $this->orderByName  = $sortName;
        $this->orderBy = $sortBy;
    }

    public function map($appointment): array
    {

        return [
            $this->serialCount++,
            $appointment->eventVisitorInfo ? $appointment->eventVisitorInfo->getProductNames() ?? 'No products' : '',
            $appointment->visitor->name ?? '',
            $appointment->visitor->designation ?? '',
            $appointment->visitor->organization ?? '',
            $appointment->exhibitor->name ?? '',
            $appointment->scheduled_at->isoFormat('llll') ?? '',
            ucwords($appointment->status) ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Products',
            'Visitor Name',
            'Visitor Designation',
            'Visitor Organization',
            'Exhibitor Name',
            'Scheduled Datetime',
            'Status',

        ];
    }

    public function collection()
    {

        $appointmentData = Appointment::whereIn('appointments.id', $this->selectedRecordIds)
            ->when($this->orderByName == 'visitor', function ($sort) {
                $sort->join('visitors', 'appointments.visitor_id', '=', 'visitors.id')
                    ->select('appointments.*')->orderBy('visitors.name', $this->orderBy);
            })
            ->when($this->orderByName == 'designation', function ($sort) {
                $sort->join('visitors', 'appointments.visitor_id', '=', 'visitors.id')
                    ->select('appointments.*')->orderBy('visitors.designation', $this->orderBy);
            })
            ->when($this->orderByName == 'exhibitor', function ($sort) {
                $sort->join('exhibitors', 'appointments.exhibitor_id', '=', 'exhibitors.id')
                    ->select('appointments.*')->orderBy('exhibitors.name', $this->orderBy);
            })
            ->when($this->orderByName == 'scheduled_at', function ($sort) {
                $sort->orderBy($this->orderByName, $this->orderBy);
            })
            ->when($this->orderByName == 'status', function ($sort) {
                $sort->orderBy($this->orderByName, $this->orderBy);
            }) ->get();
        return $appointmentData;
    }
}
