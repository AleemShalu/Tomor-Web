<?php

namespace App\Http\Controllers\Web\Admin\Order;

use App\Enums\OrderStatusEnum;
use App\Exports\Admin\Order\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $statusColors = collect(OrderStatusEnum::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->getColor()];
        });

        $orders = Order::with('store.branches', 'order_items', 'order_ratings', 'bank_card', 'customer', 'customer_vehicle', 'employee.employee_roles')->get();

        return view('admin.order.index', compact('orders', 'statusColors'));
    }

    public function show($id)
    {
        $order = Order::with('store', 'store_branch', 'order_items.product.images', 'order_items.product.translations')->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:processing,completed,delivering,delivered',
        ]);

        $order = Order::findOrFail($id);

        $order->status = $validatedData['status'];
        $order->save();

        return redirect()->route('admin.order.edit', ['id' => $order->id])->with('success', 'Order status updated successfully.');
    }

    public function orderReportsPdf()
    {
        $orders = Order::all();
        $pdf = PDF::loadView('admin.export.order.orders', compact('orders'));
        return $pdf->download('orders.pdf');
    }

    public function orderReportsExcel()
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }

    public function orderReportsCsv()
    {
        return Excel::download(new OrderExport, 'orders.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
