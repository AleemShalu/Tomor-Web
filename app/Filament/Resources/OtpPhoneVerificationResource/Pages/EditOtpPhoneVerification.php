<?php

namespace App\Filament\Resources\OtpPhoneVerificationResource\Pages;

use App\Filament\Resources\OtpPhoneVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOtpPhoneVerification extends EditRecord
{
    protected static string $resource = OtpPhoneVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
