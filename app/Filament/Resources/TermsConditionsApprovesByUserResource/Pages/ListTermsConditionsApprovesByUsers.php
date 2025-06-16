<?php

namespace App\Filament\Resources\TermsConditionsApprovesByUserResource\Pages;

use App\Filament\Resources\TermsConditionsApprovesByUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsConditionsApprovesByUsers extends ListRecords
{
    protected static string $resource = TermsConditionsApprovesByUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
