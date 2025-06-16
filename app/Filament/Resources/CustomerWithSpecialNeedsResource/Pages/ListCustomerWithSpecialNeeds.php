<?php

namespace App\Filament\Resources\CustomerWithSpecialNeedsResource\Pages;

use App\Filament\Resources\CustomerWithSpecialNeedsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerWithSpecialNeeds extends ListRecords
{
    protected static string $resource = CustomerWithSpecialNeedsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
