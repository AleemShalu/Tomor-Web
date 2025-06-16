<?php

namespace App\Filament\Resources\VatResource\Pages;

use App\Filament\Resources\VatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVats extends ListRecords
{
    protected static string $resource = VatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
