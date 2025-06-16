<?php

namespace App\Filament\Resources\BranchLocationResource\Pages;

use App\Filament\Resources\BranchLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBranchLocation extends EditRecord
{
    protected static string $resource = BranchLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
