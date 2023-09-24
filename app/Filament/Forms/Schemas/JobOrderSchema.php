<?php

namespace App\Filament\Forms\Schemas;

use App\Filament\Forms\Flatpickr;
use App\Models\Address;
use App\Models\Client;
use App\Models\Entom;
use App\Models\JobOrderType;
use App\Models\Site;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Support\Arr;

class JobOrderSchema
{
    private static function getList($contractType = '')
    {

        $types = [];
        if ($contractType) {
            $types = JobOrderType::where('contract_type_list', 'like', '%' . $contractType . '%')->get();
        }

        if (count($types) == 0) {
            $types = JobOrderType::all();
        }
        $list = [];
        foreach ($types as $type) {
            $list[$type->name] = $type->name;
        }
        return $list;
    }
    public static function getSchema(): array
    {
        return [
            TextInput::make('code')
                ->default(function (HasRelationshipTable $livewire) {
                    return $livewire->ownerRecord->code . '-' . count($livewire->ownerRecord->jobOrders) + 1;
                })
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Select::make('job_order_type')
                ->required()
                ->options(function (HasRelationshipTable $livewire) {
                    return static::getList($livewire->ownerRecord->contract_type);
                })
                ->searchable()
                ->default(function (HasRelationshipTable $livewire) {
                    return Arr::first(static::getList($livewire->ownerRecord->contract_type));
                }),

            Select::make('address_id')
                ->label('Address')
                ->options(function (HasRelationshipTable $livewire) {

                    $client = Client::find($livewire->ownerRecord->client->id);

                    if ($client) {
                        return $client->addresses()->pluck('street', 'id');
                    }
                })
                ->default(function (HasRelationshipTable $livewire) {
                    if ($livewire->ownerRecord->addresses?->first()->id) {
                        return $livewire->ownerRecord->addresses->first()->id;
                    }
                    $address = Address::where('client_id', $livewire->ownerRecord->client->id)->first();
                    return $address->id;
                })
                ->required()
                ->reactive()
                ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                    $site = Site::where('address_id', $state)->first();
                    $set('site_id', $site->id);
                }),
            Select::make('sites')
                ->relationship('sites', 'label')
                ->label('Site')
                ->multiple()
                ->options(function (callable $get) {
                    $address = Address::find($get('address_id'));
                    if (!$address) {
                        return [];
                    }
                    return $address->sites()->pluck('label', 'id');
                })
                ->default(function (\Filament\Forms\Get $get) {
                    return Site::where('address_id', $get('address_id'))->first()?->id;
                })
                ->required()
                ->reactive(),
            Select::make('status')
                ->required()
                ->options(
                    function (HasRelationshipTable $livewire) {

                        $statuses  = config('tbss.job_order_status');

                        if ($livewire->mountedTableAction === 'create') {
                            return [Arr::first($statuses) => Arr::first($statuses)];
                        }

                        return $statuses;
                    }
                )
                ->default(Arr::first(config('tbss.job_order_status'))),

            Flatpickr::make('target_date')->default(function (HasRelationshipTable $livewire) {
                $lastJobOrder = $livewire->ownerRecord->latestJobOrder;
                if ($lastJobOrder) {
                    return  Carbon::createFromDate($lastJobOrder->target_date)->addDays(30);
                }
                return $livewire->ownerRecord->start;
            })
                ->enableTime(true)
                ->altFormat('F j, Y h:i K'),

            Textarea::make('summary')
                ->default(function (HasRelationshipTable $livewire) {
                    $lastJobOrder = $livewire->ownerRecord->latestJobOrder;
                    if (is_null($lastJobOrder)) return "";
                    return $lastJobOrder->summary;
                })
                ->rows(2)
                ->required(),
            Select::make('createdBy')
                ->relationship('createdBy', 'name')
                ->default(fn () => auth()->user()->id)
                ->required(),
            Hidden::make('client_id')
                ->default(function (HasRelationshipTable $livewire) {
                    return $livewire->ownerRecord->client->id;
                })
        ];
    }
}
