<?php

namespace App\Filament\Forms\Schemas;

use Closure;
use Filament\Forms\Components\TextInput;
use Carbon\Carbon;
use Filament\Forms\Components\KeyValue;
use Illuminate\Support\Str;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Forms\Components\Select;

class ClientSchema
{

    public static function getSchema(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            Select::make('classification')
                ->options(config('tbss.client_classification'))
                ->required(),
            TableRepeater::make('contact_information')
                ->schema([
                    Select::make('type')
                        ->options([
                            'mobile' => 'mobile',
                            'email' => 'email',
                            'home' => 'home',
                            'office' => 'office',
                            'fax' => 'fax',
                        ])
                        ->default('mobile'),
                    TextInput::make('value')
                ])
                ->createItemButtonLabel('Add another number'),
        ];
    }
}
