<?php

namespace App\Filament\Forms\Schemas;

use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;

class AddressSchema
{
    public static function getSchema(): array
    {
        return [
            Select::make('region_id')
                ->label('Region')
                ->reactive()
                ->required()
                ->options(Region::all()->pluck('name', 'id'))
                ->afterStateUpdated(function (callable $set) {
                    $set('province_id', null);
                    $set('city_id', null);
                    $set('barangay_id', null);
                    $set('street_id', null);
                }),
            Select::make('province_id')
                ->label('District / Province')
                ->options(function (callable $get) {
                    $region = Region::find($get('region_id'));

                    if (!$region) {
                        return [];
                    }

                    return $region->provinces()->pluck('name', 'id');
                })
                ->reactive()
                ->required()
                ->afterStateUpdated(function (callable $set) {
                    $set('city_id', null);
                    $set('barangay_id', null);
                    $set('street_id', null);
                }),
            Select::make('city_id')
                ->label('City / Municipality / Sub-municipality')
                ->options(function (callable $get) {
                    $province = Province::find($get('province_id'));

                    if (!$province) {
                        return [];
                    }

                    return $province->cities()->pluck('name', 'id');
                })
                ->reactive()
                ->required()
                ->afterStateUpdated(function (callable $set) {
                    $set('barangay_id', null);
                    $set('street_id', null);
                }),
            Select::make('barangay_id')
                ->label('Barangay')
                ->options(function (callable $get) {
                    $city = City::find($get('city_id'));

                    if (!$city) {
                        return [];
                    }

                    return $city->barangays()->pluck('name', 'id');
                })
                // ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('street', null)),
            TextInput::make('street')->required(),
            TextInput::make('latitude')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $set('location', [
                        'lat' => floatVal($state),
                        'lng' => floatVal($get('longitude')),
                    ]);
                })
                ->lazy(),
            TextInput::make('longitude')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $set('location', [
                        'lat' => floatval($get('latitude')),
                        'lng' => floatVal($state),
                    ]);
                })
                ->lazy(),
            // TextInput::make('full_address'),
            Map::make('location')
                ->mapControls([
                    'mapTypeControl'    => false,
                    'scaleControl'      => true,
                    'streetViewControl' => false,
                    'rotateControl'     => true,
                    'fullscreenControl' => true,
                    'searchBoxControl'  => false, // creates geocomplete field inside map
                    'zoomControl'       => true,
                ])
                ->debug() // prints reverse geocode format strings to the debug console 
                ->defaultLocation([14.444546, 120.9938736])
                ->height(fn () => '500px')
                ->defaultZoom(15)
                ->draggable() // allow dragging to move marker
                ->clickable(true) // allow clicking to move marker
                ->reactive()
                ->autocomplete('street')
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $set('latitude', $state['lat']);
                    $set('longitude', $state['lng']);
                }),
        ];
    }
}
