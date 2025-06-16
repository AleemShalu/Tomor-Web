<?php

namespace App\Filament\Resources\ProductOfferResource\Pages;

use App\Filament\Resources\ProductOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductOffer extends EditRecord
{
    protected static string $resource = ProductOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
