<?php

namespace App\Filament\Resources\StoreTermsResource\Pages;

use App\Filament\Resources\StoreTermsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreTerms extends EditRecord
{
    protected static string $resource = StoreTermsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
