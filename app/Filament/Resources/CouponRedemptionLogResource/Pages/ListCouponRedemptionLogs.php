<?php

namespace App\Filament\Resources\CouponRedemptionLogResource\Pages;

use App\Filament\Resources\CouponRedemptionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponRedemptionLogs extends ListRecords
{
    protected static string $resource = CouponRedemptionLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
