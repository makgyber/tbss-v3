<?php

namespace App\Filament\Forms\Schemas;

use App\Models\Address;
use App\Models\Client;
use App\Models\ContractType;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContractSchema
{
    public static function getSchema(bool $hideClient = false): array
    {
        return [
            Select::make('client_id')
                ->label('Client')
                ->relationship('client', 'name')
                ->searchable()
                ->required()
                ->reactive()
                ->hidden($hideClient),
            CheckboxList::make('addresses')
                ->relationship('addresses', 'street', function (callable $get, $livewire) {

                    $client = Client::find($get('client_id'));

                    if (!$client && Str::contains(get_class($livewire), 'RelationManager')) {
                        $client = Client::find($livewire->ownerRecord->id);
                    }

                    if ($client) {
                        return $client->addresses();
                    }
                    return Address::where('client_id', '-1');
                })
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->street}, {$record->city->name}"),

            TextInput::make("code")
                ->unique(ignoreRecord: true)
                ->required(),
            Select::make('contract_type')
                ->required()
                ->options(function () {
                    $types = ContractType::all();
                    $list = [];
                    foreach ($types as $type) {
                        $list[$type->name] = $type->name;
                    }
                    return $list;
                }),
            TextInput::make('visits')
                ->default(1)
                ->required()
                ->numeric(),
            Select::make('frequency')
                ->required()
                ->options(config('tbss.visit_frequency')),
            DatePicker::make('start')
                ->required(),
            DatePicker::make('end')
                ->afterOrEqual('start')
                ->required(),

            TextInput::make("contract_price")
                ->numeric()
                ->required(),

            Select::make('status')
                ->required()
                ->options(function ($record) {
                    if (!$record) {
                        return [Arr::first(config('tbss.contract_status')) => Arr::first(config('tbss.contract_status'))];
                    }
                    return config('tbss.contract_status');
                }),
            Select::make('payment_status')
                ->required()
                ->options([
                    'balance' => 'balance',
                    'paid' => 'paid'
                ]),
            TextInput::make("payment_terms"),
            Select::make('assigned_to')
                ->required()
                ->options(fn () => User::whereHas('roles', function ($q) {
                    $q->where('name', 'Sales');
                })->pluck('name', 'id')),
            SpatieMediaLibraryFileUpload::make('document')
                ->preserveFilenames()
                ->enableDownload()
                ->enableOpen()
                ->multiple()
                ->enableReordering()
                ->collection("contracts"),


        ];
    }
}
