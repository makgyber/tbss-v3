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
use AlexJustesen\FilamentSpatieLaravelActivitylog\RelationManagers\ActivitiesRelationManager;
use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\ClientSchema;
use App\Filament\Forms\Schemas\SiteSchema;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Carbon\Carbon;

class ClientForm
{
    public static function getSchema()
    {

        return [
            ...ClientSchema::getSchema(),
            Repeater::make('addresses')
                ->hint('You can add multiple addresses')
                ->relationship('addresses')
                ->schema([
                    ...AddressSchema::getSchema(),
                    Repeater::make('sites')
                        ->relationship('sites')
                        ->schema([...SiteSchema::getSchema()])
                        ->createItemButtonLabel('Add another site'),
                ])
                ->createItemButtonLabel('Add another address'),
        ];
    }
}
