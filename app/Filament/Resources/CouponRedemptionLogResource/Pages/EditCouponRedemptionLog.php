<?php

namespace App\Filament\Resources\CouponRedemptionLogResource\Pages;

use App\Filament\Resources\CouponRedemptionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCouponRedemptionLog extends EditRecord
{
    protected static string $resource = CouponRedemptionLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
