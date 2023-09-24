<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\ClientSchema;
use App\Filament\Forms\Schemas\LeadDetailSchema;
use App\Filament\Resources\LeadResource;
use App\Filament\Resources\LeadResource\Widgets\AddressList;
use App\Filament\Resources\LeadResource\Widgets\ClientWidget;
use App\Filament\Resources\LeadResource\Widgets\LeadDetailWidget;
use App\Filament\Resources\LeadResource\Widgets\EntomDetails;
use App\Filament\Resources\LeadResource\Widgets\JobOrderDetails;
use Filament\Forms\Components\Card;
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

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;


    protected function getFormSchema(): array
    {

        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LeadDetailWidget::class,
            ClientWidget::class,
            EntomDetails::class,
            JobOrderDetails::class,
        ];
    }
}
