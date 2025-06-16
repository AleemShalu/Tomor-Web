<?php

namespace App\View\Components;

use App\Models\Store;
use Illuminate\View\Component;

class StoreMain extends Component
{
    public $store;

    public function __construct($storeId)
    {
        $this->store = Store::find($storeId);
    }

    public function render()
    {
        return view('components.store-main');
    }
}