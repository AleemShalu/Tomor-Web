<?php

namespace App\Filament\Resources\BankCardResource\Pages;

use App\Filament\Resources\BankCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankCard extends EditRecord
{
    protected static string $resource = BankCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
