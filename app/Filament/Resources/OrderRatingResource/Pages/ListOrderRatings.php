<?php

namespace App\Filament\Resources\OrderRatingResource\Pages;

use App\Filament\Resources\OrderRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderRatings extends ListRecords
{
    protected static string $resource = OrderRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
