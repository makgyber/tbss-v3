<?php

namespace App\Filament\Forms\Schemas;

use App\Models\Option;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SiteSchema
{
    public static function getSchema(): array
    {
        return [
            Select::make('type')
                ->options([
                    'residential' => 'residential',
                    'commercial' => 'commercial',
                ])->required(),
            TextInput::make('label')->required(),
            TextInput::make('contact_person'),
            TextInput::make('contact_number'),
            Repeater::make('areas')
                ->schema([
                    TextInput::make('area')->hint('describe the serviceable area')
                        ->datalist(function () {
                            return Option::where('list', 'locations')->get()->pluck('value', 'value');
                        }),
                    TextInput::make('size')->hint('how big is the serviceable area'),
                ])->columns(2)
        ];
    }
}
