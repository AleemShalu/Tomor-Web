<?php

namespace App\Filament\Resources\BankCardResource\Pages;

use App\Filament\Resources\BankCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankCards extends ListRecords
{
    protected static string $resource = BankCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
