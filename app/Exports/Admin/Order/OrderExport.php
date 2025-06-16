<?php

namespace App\Exports\Admin\Order;

use App\Models\Order; // Import the Order model
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Retrieve all orders from the database
        return Order::all();
    }
}
