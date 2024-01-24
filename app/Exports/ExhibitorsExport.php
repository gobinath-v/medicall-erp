<?php

namespace App\Exports;

use App\Models\Exhibitor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ExhibitorsExport implements FromView
{
    use Exportable;

    protected $selectedExhibitors;
    protected $eventId;

    public function __construct($eventId, $selectedExhibitors)
    {
        $this->eventId = $eventId;
        $this->selectedExhibitors = $selectedExhibitors;
    }

    public function view(): View
    {
        return view('exports.exhibitor-data', ['exhibitors' => $this->selectedExhibitors, 'eventId' => $this->eventId]);
    }


}
