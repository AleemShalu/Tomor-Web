<?php

namespace App\Filament\Resources\PlatformRatingResource\Pages;

use App\Filament\Resources\PlatformRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlatformRatings extends ListRecords
{
    protected static string $resource = PlatformRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
