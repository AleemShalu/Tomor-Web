<?php

namespace App\Filament\Resources\ProductStockInBranchResource\Pages;

use App\Filament\Resources\ProductStockInBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductStockInBranch extends EditRecord
{
    protected static string $resource = ProductStockInBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
