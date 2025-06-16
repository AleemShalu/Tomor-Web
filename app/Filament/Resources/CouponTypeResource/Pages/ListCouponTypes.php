<?php

namespace App\Filament\Resources\CouponTypeResource\Pages;

use App\Filament\Resources\CouponTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponTypes extends ListRecords
{
    protected static string $resource = CouponTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
