<?php

namespace App\Filament\Resources\LocationConfigResource\Pages;

use App\Filament\Resources\LocationConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationConfig extends EditRecord
{
    protected static string $resource = LocationConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
