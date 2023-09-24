<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Filament\Resources\LeadResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
