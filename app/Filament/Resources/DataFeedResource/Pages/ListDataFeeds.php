<?php

namespace App\Filament\Resources\DataFeedResource\Pages;

use App\Filament\Resources\DataFeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataFeeds extends ListRecords
{
    protected static string $resource = DataFeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
