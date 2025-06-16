<?php

namespace App\Http\Controllers\Web\Owner;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function show()
    {
        $users = User::all();
        return view('status', compact('users'));
    }

    public function exportEmployees(Request $request)
    {
        $exportType = $request->input('export_type', 'pdf'); // Retrieve the export type from the request, defaulting to 'pdf'

        $export = new UsersExport();

        if ($exportType === 'pdf') {
            $pdf = new Dompdf();

            $pdf->loadHtml(View::make('export.users', ['export' => $export])->render());
            $pdf->setPaper('A4');

            $pdf->render();

            return $pdf->stream('users.pdf');
        } elseif ($exportType === 'xlsx') {
            return $export->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } elseif ($exportType === 'csv') {
            return $export->download('users.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }
}
