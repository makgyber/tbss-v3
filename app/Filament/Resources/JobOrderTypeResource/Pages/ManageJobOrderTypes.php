<?php

namespace App\Filament\Resources\JobOrderTypeResource\Pages;

use App\Filament\Resources\JobOrderTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJobOrderTypes extends ManageRecords
{
    protected static string $resource = JobOrderTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
