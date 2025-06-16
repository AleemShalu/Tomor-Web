<?php

namespace App\Filament\Resources\UsherResource\Pages;

use App\Filament\Resources\UsherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUshers extends ListRecords
{
    protected static string $resource = UsherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
