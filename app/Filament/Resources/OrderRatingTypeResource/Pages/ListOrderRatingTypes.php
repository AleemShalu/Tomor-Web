<?php

namespace App\Filament\Resources\OrderRatingTypeResource\Pages;

use App\Filament\Resources\OrderRatingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderRatingTypes extends ListRecords
{
    protected static string $resource = OrderRatingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
