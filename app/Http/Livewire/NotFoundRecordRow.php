<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotFoundRecordRow extends Component
{
    public $colspan;

    public function mount($colspan = 4)
    {
        $this->colspan = $colspan;
    }
    public function render()
    {
        return <<<'blade'
            <tr>
                <td colspan="{{$this->colspan}}" class="text-center text-danger">
                    <strong>Record not found.</strong>
                </td>
            </tr>
        blade;
    }
}
