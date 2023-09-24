<?php

namespace App\Filament\Forms;

use App\Models\Client;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Carbon\Carbon;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;

class ClientFormWizard
{
    public static function getSchema()
    {
        return [

            Wizard::make([
                Step::make('Inquiry Particulars')
                    ->schema([
                        TextInput::make('name')
                            ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set, ?string $state) {
                                if (!$get('is_slug_changed_manually') && filled($state)) {
                                    $set('slug', Carbon::now()->format('Ym') . '-' . Str::slug($state));
                                }
                            })
                            ->reactive()
                            ->required(),
                        TextInput::make('slug')
                            ->afterStateUpdated(function (\Filament\Forms\Set $set) {
                                $set('is_slug_changed_manually', true);
                            })
                            ->maxLength(255)
                            ->rules(['alpha_dash'])
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Hidden::make('is_slug_changed_manually')
                            ->default(false)
                            ->dehydrated(false),
                        TableRepeater::make('contact_information')
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'email' => 'email',
                                        'mobile' => 'mobile',
                                        'home' => 'home',
                                        'office' => 'office',
                                        'fax' => 'fax',
                                    ]),
                                TextInput::make('value')
                            ]),
                    ]),
                Step::make('Address Information')
                    ->schema([
                        Repeater::make('addresses')
                            ->hint('You can add multiple addresses')
                            ->relationship('addresses')
                            ->schema([

                                Repeater::make('sites')
                                    ->schema([])
                            ]),
                    ])
            ])



        ];
    }
}
