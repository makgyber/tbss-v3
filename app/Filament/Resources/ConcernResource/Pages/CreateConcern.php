<?php

namespace App\Filament\Resources\ConcernResource\Pages;

use App\Filament\Resources\ConcernResource;
use App\Models\Concern;
use App\Models\Resolution;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConcern extends CreateRecord
{
    protected static string $resource = ConcernResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->id;
        $data['status'] = 'pending';
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->resolutions()->create([
            'created_by' => $this->record->created_by,
            'assigned_to' => $this->record->assigned_to,
            'details' => 'concern initiated'
        ]);
    }
}
