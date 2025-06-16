<?php

namespace App\Filament\Resources\StoreBranchResource\Pages;

use App\Filament\Resources\StoreBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreBranch extends EditRecord
{
    protected static string $resource = StoreBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
