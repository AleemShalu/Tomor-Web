<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEmployee implements FromCollection, WithHeadings
{
    use Exportable;

    private $storeId;
    private $userId;

    public function __construct($storeId, $userId)
    {
        $this->storeId = $storeId;
        $this->userId = $userId;
    }

    public function collection()
    {
        return User::with(['employee_branches', 'employee_roles'])
            ->where('store_id', $this->storeId)
            ->where('id', $this->userId)
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
