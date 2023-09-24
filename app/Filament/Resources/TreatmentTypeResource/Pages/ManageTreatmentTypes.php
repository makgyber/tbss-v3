<?php

namespace App\Filament\Resources\TreatmentTypeResource\Pages;

use App\Filament\Resources\TreatmentTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTreatmentTypes extends ManageRecords
{
    protected static string $resource = TreatmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
