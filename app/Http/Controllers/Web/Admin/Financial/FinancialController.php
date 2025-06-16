<?php

namespace App\Http\Controllers\Web\Admin\Financial;

use App\Exports\Admin\Invoice\InvoicesExport;
use App\Exports\Admin\Invoice\InvoicesStoreExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.financial.index');
    }

    public function indexInvoices()
    {
        return view('admin.financial.invoices.index');
    }

    public function indexStoreInvoices($id)
    {
        return view('admin.financial.invoices.store.index', compact('id'));
    }


    public function fetchInvoices(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = 30; // Set your desired page size

        // Search by invoice_number
        if ($request->has('id')) {
            // Base query
            $query = Invoice::query()->where('store_id', $request->input('id'));
        } else {
            // Base query
            $query = Invoice::query();
        }

        // Search by invoice_number
        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->input('search') . '%');
        }

        // Filter by status if the 'status' parameter is not empty
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Paginate the results
        $invoices = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($invoices);
    }

    public function exportInvoices()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function exportStoreInvoices($id)
    {
        return Excel::download(new InvoicesStoreExport($id), 'invoices.xlsx');
    }

    public function showInvoice($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            abort(404, 'Invoice not found');
        }

        $invoicePath = $invoice->path;

        // Check if the file exists
        if (Storage::disk(getSecondaryStorageDisk())->exists($invoicePath)) {
            $pdfPath = Storage::disk(getSecondaryStorageDisk())->path($invoicePath);

            // Customize the file name based on your requirements
            $fileName = 'invoice_' . $invoice->invoice_number . '.pdf';

            // If you want to open the PDF in a new window, you can use response()->file()
            // return response()->file($pdfPath);

            // If you want to force download the PDF, you can use response()->download()
            return response()->download($pdfPath, $fileName);
        } else {
            abort(404, 'PDF not found');
        }
    }


    public function indexStoreAnalysis()
    {
        return view('admin.financial.store_analysis.index');
    }


    public function fetchStoreAnalysis(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = 30; // Set your desired page size

        // Base query
        $query = Store::with('order_service.order.invoice');

        $query = Store::with([
            'order_service' => function ($query) {
                $query->select('store_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(price) as total_price'))
                    ->groupBy('store_id');
            },
            'order_service.order.invoice',
        ]);

        // Paginate the results
        $stores = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($stores);
    }

}
