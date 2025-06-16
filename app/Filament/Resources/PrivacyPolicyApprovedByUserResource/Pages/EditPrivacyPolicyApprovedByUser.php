<?php

namespace App\Filament\Resources\PrivacyPolicyApprovedByUserResource\Pages;

use App\Filament\Resources\PrivacyPolicyApprovedByUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrivacyPolicyApprovedByUser extends EditRecord
{
    protected static string $resource = PrivacyPolicyApprovedByUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
