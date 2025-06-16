<?php

namespace App\Filament\Resources\TermsConditionResource\Pages;

use App\Filament\Resources\TermsConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTermsCondition extends EditRecord
{
    protected static string $resource = TermsConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
