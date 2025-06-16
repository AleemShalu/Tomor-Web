<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Barryvdh\DomPDF\Facade as PDF;

class UsersExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection(): \Illuminate\Support\Collection
    {
        $id = request()->input('id'); // Retrieve the value of 'id' from the request

        return User::with(['employee_branches', 'employee_roles'])
            ->whereHas('employee_branches', function ($query) use ($id) {
                $query->where('store_branch_id', $id);
            })
            ->select('id', 'name', 'email', 'password', 'store_id', 'created_at', 'updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Password',
            'Store ID',
            'Created At',
            'Updated At',
        ];
    }
}
