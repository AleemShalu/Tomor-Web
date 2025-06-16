<?php

namespace App\Filament\Resources\BranchVisitorResource\Pages;

use App\Filament\Resources\BranchVisitorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBranchVisitor extends EditRecord
{
    protected static string $resource = BranchVisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
