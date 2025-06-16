<?php

namespace App\Filament\Resources\OtpEmailVerificationResource\Pages;

use App\Filament\Resources\OtpEmailVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtpEmailVerifications extends ListRecords
{
    protected static string $resource = OtpEmailVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
