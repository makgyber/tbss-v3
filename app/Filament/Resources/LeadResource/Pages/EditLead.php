<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\LeadDetailSchema;
use App\Filament\Resources\LeadResource;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getFormSchema(): array
    {

        return [
            Section::make('Lead Details')
                ->columns(2)
                ->schema([
                    ...LeadDetailSchema::getSchema(),
                    Select::make('status')
                        ->options(config('tbss.lead_status'))
                        ->required(),
                    Select::make('assigned_to')
                        ->options(User::all()->pluck('name', 'id'))
                        ->required(),
                    Select::make('source')
                        ->options(config('tbss.lead_source')),
                    Select::make('service_type')
                        ->options(config('tbss.service_type')),
                    DatePicker::make('received_on')
                        ->required(),
                ])
                ->collapsible(),

        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
