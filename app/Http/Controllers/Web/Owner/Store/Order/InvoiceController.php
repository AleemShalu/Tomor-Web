<?php

namespace App\Http\Controllers\Web\Owner\Store\Order;

use App\Exports\InvoiceExport;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }


    public function exportPDF($branchId)
    {
        $invoices = Invoice::with('order')
            ->whereHas('order', function ($query) use ($branchId) {
                $query->where('store_branch_id', $branchId);
            })
            ->get();

        $pdf = PDF::loadView('export.invoices', compact('invoices'));
        return $pdf->download('invoices.pdf');
    }

    public function exportExcel($branchId)
    {
        return Excel::download(new InvoiceExport($branchId), 'invoices.xlsx');
    }

    public function exportCSV($branchId)
    {
        return Excel::download(new InvoiceExport($branchId), 'invoices.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    function printInvoicePDF($invoiceId)
    {
        $invoice = Invoice::with('order')->find($invoiceId);
        $pdf = PDF::loadView('export.invoice', compact('invoice'));

        return $pdf->stream('invoice' . $invoiceId . '.pdf', ['Attachment' => false]);
    }

    public function previewInvoice($invoiceId)
    {
        $invoice = Invoice::with('order')->find($invoiceId);

        return view('owner.store.branch-manage.order-management.invoice-preview', compact('invoiceId', 'invoice'));
    }
}
