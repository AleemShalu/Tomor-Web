<?php

namespace App\Filament\Resources\CustomerWithSpecialNeedsResource\Pages;

use App\Filament\Resources\CustomerWithSpecialNeedsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerWithSpecialNeeds extends CreateRecord
{
    protected static string $resource = CustomerWithSpecialNeedsResource::class;
}
