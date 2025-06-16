<?php

namespace App\Http\Controllers\Web\Owner\Store\Order;

use App\Exports\OrdersExport;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function generateReport($orderId)
    {
        // Get the order data based on the provided ID
        $order = Order::with('rating', 'user')->findOrFail($orderId);

        // Load the view template
        $view = view('export.order-report', compact('order'));

        // Configure the PDF options
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Instantiate the Dompdf class
        $dompdf = new Dompdf($options);

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($view);

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the PDF as a download
        return $dompdf->stream('order_report.pdf');
    }

    public function exportByBranch($branchId)
    {
        $orders = Order::with('user')->where('store_branch_id', $branchId)->get();

        // You can return a view to select export options or proceed directly to export.
        return view('orders.export_by_branch', compact('orders', 'branchId'));
    }

    public function exportPDF($branchId)
    {
        $orders = Order::with('user')->where('store_branch_id', $branchId)->get();
        $pdf = PDF::loadView('export.orders', compact('orders'));

        return $pdf->stream('orders_branch' . $branchId . '.pdf', ['Attachment' => false]);
    }

    public function exportExcel($branchId)
    {
        $orders = Order::with('user')->where('store_branch_id', $branchId)->get();

        return Excel::download(new OrdersExport([$orders]), 'orders_branch' . $branchId . '.xlsx');
    }

    public function exportCSV($branchId)
    {
        $orders = Order::with('user')->where('store_branch_id', $branchId)->get();

        return Excel::download(new OrdersExport($orders), 'orders_branch' . $branchId . '.csv');
    }

}
