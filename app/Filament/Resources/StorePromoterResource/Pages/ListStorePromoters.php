<?php

namespace App\Filament\Resources\StorePromoterResource\Pages;

use App\Filament\Resources\StorePromoterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStorePromoters extends ListRecords
{
    protected static string $resource = StorePromoterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
