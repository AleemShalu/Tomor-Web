<?php

namespace App\Exports\Api;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromView, WithStyles, ShouldAutoSize
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
        $duration = $this->exportFields['duration'];
        $user = $this->exportFields['user'];
        $store = $this->exportFields['store'];
        $branch = $this->exportFields['branch'];
        $employees = $this->exportFields['employees'];

        return view('export.api.employees.branch_employees_sheet', [
            'report_title' => $report_title,
            'duration' => $duration,
            'user' => $user,
            'store' => $store,
            'branch' => $branch,
            'employees' => $employees,
        ]);
    }
}
