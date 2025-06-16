<?php

namespace App\Filament\Resources\NotificationsGroupResource\Pages;

use App\Filament\Resources\NotificationsGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotificationsGroups extends ListRecords
{
    protected static string $resource = NotificationsGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
