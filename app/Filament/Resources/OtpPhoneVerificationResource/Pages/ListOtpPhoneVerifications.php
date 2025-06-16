<?php

namespace App\Filament\Resources\OtpPhoneVerificationResource\Pages;

use App\Filament\Resources\OtpPhoneVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtpPhoneVerifications extends ListRecords
{
    protected static string $resource = OtpPhoneVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
