<?php

namespace App\Filament\Resources\BusinessPolicyResource\Pages;

use App\Filament\Resources\BusinessPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessPolicies extends ListRecords
{
    protected static string $resource = BusinessPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
