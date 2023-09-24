<?php

namespace App\Filament\Resources\EntomResource\Pages;

use App\Filament\Forms\Schemas\CreateEntomSchema;
use App\Filament\Resources\EntomResource;
use App\Models\Lead;
use Filament\Forms\Components\Fieldset;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEntom extends CreateRecord
{
    protected static string $resource = EntomResource::class;

    protected function getFormSchema(): array
    {
        $lead = null;
        if (request('ownerRecord')) {
            $lead = Lead::where('id', request('ownerRecord'))->first();
        }

        return [
            Fieldset::make('Entom Details')
                ->schema(
                    CreateEntomSchema::getSchema($lead),
                )
                ->columns(2)
        ];
    }

    protected function afterCreate()
    {
        // $lead = Lead::where('id', $this->data['lead_id'])->first();
        // $lead->status = $this->data['status'];
        // $lead->save();
    }
}
