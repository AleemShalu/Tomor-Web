<?php

namespace App\Filament\Resources\UsherClientResource\Pages;

use App\Filament\Resources\UsherClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsherClients extends ListRecords
{
    protected static string $resource = UsherClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
