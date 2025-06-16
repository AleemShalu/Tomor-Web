<?php

namespace App\Filament\Resources\SpecialNeedsTypeResource\Pages;

use App\Filament\Resources\SpecialNeedsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialNeedsType extends EditRecord
{
    protected static string $resource = SpecialNeedsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
