<?php

namespace App\Filament\Forms\Schemas;

use Illuminate\Database\Eloquent\Model;
use App\Filament\Forms\ClientForm;
use App\Models\Address;
use App\Models\Client;
use App\Models\User;
use App\Models\Lead;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class CreateEntomSchema
{
    public static function getSchema(Lead $record = null): array
    {
        return [
            Select::make('client_id')
                ->default(function () use ($record) {
                    if ($record) {
                        return $record->client_id;
                    }
                    return null;
                })
                ->label('Client')
                ->required()
                ->relationship('client', 'name')
                ->createOptionForm([
                    ...ClientForm::getSchema()
                ])
                ->reactive()
                ->searchable(),
            DateTimePicker::make('target_date')
                ->default(Carbon::now())
                ->timezone('Asia/Manila'),
            Select::make('address_id')
                ->label('Address')
                ->options(function (callable $get) {
                    $client = Client::find($get('client_id'));

                    if ($client) {
                        return $client->addresses()->pluck('street', 'id');
                    }
                })
                ->required()
                ->reactive(),
            Select::make('site_id')
                ->label('Site')
                ->options(function (callable $get) {

                    $address = Address::find($get('address_id'));

                    if (!$address) {
                        return [];
                    }

                    return $address->sites()->pluck('label', 'id');
                })
                ->required()
                ->reactive(),

            Select::make('assigned_to')
                ->options(User::all()->pluck('name', 'id'))
                ->required(),
            Textarea::make('client_requests')->default(function () use ($record) {

                if ($record) {
                    return $record->concerns;
                }

                return null;
            })->nullable(),
            Hidden::make('requested_by')->default(auth()->user()->id),
            Hidden::make('status')->default('pending entom'),
            Hidden::make('lead_id')->default(function () use ($record) {
                if ($record) {
                    return $record->id;
                }
                return null;
            }),
        ];
    }
}
