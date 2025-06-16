<?php

namespace App\Http\Livewire;

use App\Models\Report;
use Livewire\Component;

class SearchComplaint extends Component
{
    public $search = '';

    public function render()
    {
        $report_type_id = 2;
        $complaints = Report::whereHas('report_subtype', function ($query) use ($report_type_id) {
            $query->where('report_type_id', $report_type_id);

            if ($this->search !== '') {
                // Use "ticket_id" field for exact search filtering
                $query->where('ticket_id', $this->search);
            }
        })->get();

        return view('livewire.search-complaint', compact('complaints'));
    }

}
