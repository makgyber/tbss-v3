<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Lead;
use App\Models\Address;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;


class AddressList extends BaseWidget
{
    public ?Lead $record;
    protected function getTableQuery(): Builder
    {

        return Address::where('client_id', $this->record->client->id);
    }

    public function mount(Lead $record)
    {
        $this->record = $record;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('region.name'),
            View::make('filament.resources.lead-resource.widgets.lead-site-column-table')
                ->components([
                    TextColumn::make('province.name'),
                    TextColumn::make('city.name'),
                    TextColumn::make('barangay.name'),
                    TextColumn::make('street'),
                ]),
        ];
    }
}
