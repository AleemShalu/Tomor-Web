<?php

namespace App\Filament\Resources\ProductStockInBranchResource\Pages;

use App\Filament\Resources\ProductStockInBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStockInBranches extends ListRecords
{
    protected static string $resource = ProductStockInBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
