<?php

namespace App\Filament\Resources\ServiceDefinitionResource\Pages;

use App\Filament\Resources\ServiceDefinitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceDefinition extends EditRecord
{
    protected static string $resource = ServiceDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
