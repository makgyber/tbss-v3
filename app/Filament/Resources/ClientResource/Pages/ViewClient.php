<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\ClientSchema;
use App\Filament\Forms\Schemas\LeadDetailSchema;
use App\Filament\Resources\ClientResource;
use App\Filament\Resources\LeadResource;
use App\Filament\Resources\LeadResource\Widgets\AddressList;
use App\Filament\Resources\LeadResource\Widgets\ClientWidget;
use App\Filament\Resources\LeadResource\Widgets\LeadDetailWidget;
use App\Filament\Resources\LeadResource\Widgets\EntomDetails;
use App\Filament\Resources\LeadResource\Widgets\JobOrderDetails;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    public function getHeading(): string | Htmlable
    {
        return $this->record->name . " " . $this->record->classification;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return view(
            'filament.resources.common.contact-information',
            ['contacts' => $this->record->contact_information]
        );
    }

    protected function getFormSchema(): array
    {

        return [
            Section::make('Contracts')
                ->schema([
                    Repeater::make('contracts')
                        ->relationship('contracts')
                        ->schema([
                            Placeholder::make('code'),
                            Placeholder::make('address')
                                ->content('')
                        ]),

                ])

            // Tabs::make('Service History')
            //     ->schema([
            //         // Tab::make('Client Details')
            //         //     ->schema(
            //         //         [View::make('filament.resources.client-resource.tabs.client-info'),]
            //         //     ),
            //         Tab::make('Locations')
            //             ->schema(
            //                 [
            //                     View::make('filament.resources.client-resource.tabs.address-info'),
            //                 ]
            //             ),
            //         Tab::make('Lead Details')
            //             ->schema([
            //                 View::make('filament.resources.client-resource.tabs.lead-detail'),
            //             ]),
            //         Tab::make('Entom Details')
            //             ->schema([
            //                 View::make('filament.resources.client-resource.tabs.entom-detail'),
            //             ]),
            //         Tab::make('Contract Details')
            //             ->schema([
            //                 View::make('filament.resources.client-resource.tabs.contract-detail'),
            //             ]),

            //     ])
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
