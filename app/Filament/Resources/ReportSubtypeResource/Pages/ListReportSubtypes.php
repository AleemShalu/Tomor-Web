<?php

namespace App\Filament\Resources\ReportSubtypeResource\Pages;

use App\Filament\Resources\ReportSubtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportSubtypes extends ListRecords
{
    protected static string $resource = ReportSubtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
