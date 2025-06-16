<?php

namespace App\Filament\Resources\BranchWorkStatusResource\Pages;

use App\Filament\Resources\BranchWorkStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranchWorkStatuses extends ListRecords
{
    protected static string $resource = BranchWorkStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
