<?php

namespace App\Filament\Resources\UsherClientResource\Pages;

use App\Filament\Resources\UsherClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsherClient extends EditRecord
{
    protected static string $resource = UsherClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
