<?php

namespace App\Filament\Resources\StoreTermsApprovedByStoreResource\Pages;

use App\Filament\Resources\StoreTermsApprovedByStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreTermsApprovedByStore extends EditRecord
{
    protected static string $resource = StoreTermsApprovedByStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
