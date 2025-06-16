<?php

namespace App\Filament\Resources\NotificationsGroupResource\Pages;

use App\Filament\Resources\NotificationsGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificationsGroup extends EditRecord
{
    protected static string $resource = NotificationsGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
