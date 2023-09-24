<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\ClientSchema;
use App\Filament\Forms\Schemas\LeadDetailSchema;
use App\Filament\Resources\EntomResource;
use App\Filament\Resources\LeadResource;
use App\Models\Address;
use App\Models\Client;
use App\Models\Entom;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateLead extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = LeadResource::class;

    protected function getSteps(): array
    {
        return [

            Step::make('Lead Details')
                ->description('Input client inquries and concerns here')
                ->columns(2)
                ->schema([
                    FieldSet::make('From client communication')
                        ->columns(1)
                        ->schema([
                            ...LeadDetailSchema::getSchema()
                        ])
                ]),

            Step::make('Client Information')
                ->description('Save client information')
                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'name')
                        ->createOptionForm([
                            ...ClientSchema::getSchema()
                        ])
                        ->reactive()
                        ->searchable()
                        ->required()
                        ->hint('Click on the + icon to add a new client'),
                ]),
            Step::make('Conclusion and Next Steps')
                ->description('Save client information')
                ->schema([
                    Select::make('status')
                        ->options(config('tbss.lead_status'))
                        ->required(),
                    Select::make('assigned_to')
                        ->searchable()
                        ->options(User::all()->pluck('name', 'id'))
                        ->required(),
                    Select::make('source')
                        ->options(config('tbss.lead_source')),
                    Select::make('service_type')
                        ->options(config('tbss.service_type')),
                    DatePicker::make('received_on')
                        ->default(now()),
                    Repeater::make('Comment')
                        ->relationship('comments')
                        ->disableItemCreation()
                        ->disableItemDeletion()
                        ->schema([
                            Toggle::make('is_important'),
                            Textarea::make('body'),
                            Hidden::make('commented_by')
                                ->default(auth()->user()->id),
                        ]),

                ])

        ];
    }

    public function hasSkippableSteps(): bool
    {
        return true;
    }

    protected function afterCreate()
    {
        $data = $this->data;
        if ($data['status'] == 'pending entom') {
            $recipient = User::find($data['assigned_to']);
            Notification::make()
                ->title('ENTOM requested from lead')
                ->body('Please check new ENTOM')
                ->sendToDatabase($recipient);
        }
    }

    protected function getRedirectUrl(): string
    {

        if ($this->data['status'] == 'pending entom') {
            return EntomResource::getUrl('create', ['ownerRecord' => $this->record->getKey()]);
        }

        return $this->getResource()::getUrl('index');
    }
}
