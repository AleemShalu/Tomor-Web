<?php

namespace App\Filament\Resources\StoreBranchResource\Pages;

use App\Filament\Resources\StoreBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreBranches extends ListRecords
{
    protected static string $resource = StoreBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
