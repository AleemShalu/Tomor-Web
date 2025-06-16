<?php

namespace App\Filament\Resources\PlatformRatingResource\Pages;

use App\Filament\Resources\PlatformRatingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlatformRating extends EditRecord
{
    protected static string $resource = PlatformRatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
