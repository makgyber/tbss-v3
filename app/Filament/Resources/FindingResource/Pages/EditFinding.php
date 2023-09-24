<?php

namespace App\Filament\Resources\FindingResource\Pages;

use App\Filament\Resources\FindingResource;
use App\Filament\Resources\JobOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinding extends EditRecord
{
    protected static string $resource = FindingResource::class;

    protected function getHeaderActions(): array
    {

        return [
            // Actions\DeleteAction::make(),
            // Actions\Action::make('View Job Order')
            //     ->url(fn () => JobOrderResource::getUrl('edit', ['record' => $this->record->jobOrder->id, 'activeRelationManager' => 2]))
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Actions\Action::make('View Job Order')
                ->url(fn () => JobOrderResource::getUrl('edit', ['record' => $this->record->jobOrder->id, 'activeRelationManager' => 2])),
            // $this->getCancelFormAction()->label('Back'),
        ];
    }
}
