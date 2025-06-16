<?php

namespace App\Filament\Resources\LocalizedNotificationResource\Pages;

use App\Filament\Resources\LocalizedNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalizedNotifications extends ListRecords
{
    protected static string $resource = LocalizedNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
