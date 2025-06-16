<?php

namespace App\Exports\Api;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BranchSalesExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $exportFields = [];

    public function __construct($fields)
    {
        $this->exportFields = $fields;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['italic' => true]],
            2 => ['font' => ['italic' => true]],
            3 => ['font' => ['italic' => true]],
            4 => ['font' => ['italic' => true]],
            5 => ['font' => ['italic' => true]],
            6 => ['font' => ['bold' => true]],
        ];
    }

    public function view(): View
    {
        $report_title = $this->exportFields['report_title'];
        $user = $this->exportFields['user'];
        $store = $this->exportFields['store'];
        $branch = $this->exportFields['branch'];
        $branch_sales = $this->exportFields['branch_sales'];

        return view('export.api.branches.branch_sales_sheet', [
            'report_title' => $report_title,
            'user' => $user,
            'store' => $store,
            'branch' => $branch,
            'branch_sales' => $branch_sales,
        ]);
    }
}
