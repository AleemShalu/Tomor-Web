<?php

namespace App\Filament\Resources\CustomerWithSpecialNeedsResource\Pages;

use App\Filament\Resources\CustomerWithSpecialNeedsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerWithSpecialNeeds extends EditRecord
{
    protected static string $resource = CustomerWithSpecialNeedsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
