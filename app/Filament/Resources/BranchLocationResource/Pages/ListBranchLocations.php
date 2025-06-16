<?php

namespace App\Filament\Resources\BranchLocationResource\Pages;

use App\Filament\Resources\BranchLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranchLocations extends ListRecords
{
    protected static string $resource = BranchLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
