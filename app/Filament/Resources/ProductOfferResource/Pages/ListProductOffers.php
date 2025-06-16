<?php

namespace App\Filament\Resources\ProductOfferResource\Pages;

use App\Filament\Resources\ProductOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductOffers extends ListRecords
{
    protected static string $resource = ProductOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
