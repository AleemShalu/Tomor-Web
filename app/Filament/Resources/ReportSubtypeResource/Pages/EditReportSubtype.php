<?php

namespace App\Filament\Resources\ReportSubtypeResource\Pages;

use App\Filament\Resources\ReportSubtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportSubtype extends EditRecord
{
    protected static string $resource = ReportSubtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
