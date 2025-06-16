<?php

namespace App\Filament\Resources\OtpEmailVerificationResource\Pages;

use App\Filament\Resources\OtpEmailVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOtpEmailVerification extends EditRecord
{
    protected static string $resource = OtpEmailVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
