<?php

namespace App\Filament\Resources\StorePromoterResource\Pages;

use App\Filament\Resources\StorePromoterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStorePromoter extends EditRecord
{
    protected static string $resource = StorePromoterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
