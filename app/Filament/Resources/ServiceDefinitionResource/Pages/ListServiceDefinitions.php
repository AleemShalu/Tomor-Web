<?php

namespace App\Filament\Resources\ServiceDefinitionResource\Pages;

use App\Filament\Resources\ServiceDefinitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceDefinitions extends ListRecords
{
    protected static string $resource = ServiceDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
