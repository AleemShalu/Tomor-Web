<?php

namespace App\Http\Controllers\Web\Owner;

use App\Exports\ExportEmployee;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\PDFExportTrait;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{

    use PDFExportTrait;

    public function exportEmployees($storeId, $userId)
    {
        $export = new ExportEmployee($storeId, $userId);

        $pdf = PDF::loadView('export.users', ['export' => $export]);
        $pdf->setPaper('A4');

        return $pdf->stream('user.pdf');
    }


    public function exportStoreDetailsPdf($storeId)
    {
        $model = Store::with('owner', 'branches', 'bank_accounts', 'employees.employee_roles', 'products.product_category', 'products.translations', 'products.images', 'business_type')->find($storeId);

        $reportName = 'Store Report';
        $viewName = 'export.pdf.store-details';

        return $this->generatePDFResponse($model, $reportName, $viewName, 'P');
    }
}
