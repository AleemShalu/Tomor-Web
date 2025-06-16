<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\StoreBranch;
use Livewire\Component;

class OrderSearch extends Component
{
    public $search = '';
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $branch = StoreBranch::with('store')->where('id', $this->id)->firstOrFail();
        $orders_count = Order::where('store_branch_id', $branch->id)->count();
        $orders = Order::with('rating', 'customer')
            ->where('store_branch_id', $branch->id)
            ->where('order_number', 'like', '%' . $this->search . '%')
            ->get();

        $totalBaseTaxable = $orders->sum('base_taxable_total');

        return view('livewire.order-search', compact('branch', 'orders_count', 'totalBaseTaxable', 'orders'));
    }
}
