<?php

namespace App\Http\Livewire;

use App\Models\Invoice;

use App\Models\StoreBranch;
use Livewire\Component;

class SearchInvoiceComponent extends Component
{
    public $query;

    public function render()
    {
        $branch = StoreBranch::find(1); // Replace 1 with the appropriate branch ID or retrieval logic

        $invoice = Invoice::where('order_id', 'like', '%' . $this->query . '%')
            ->get();

        return view('livewire.search-invoice-component', [
            'invoice' => $invoice,
            'branch' => $branch,
        ]);
    }
}
