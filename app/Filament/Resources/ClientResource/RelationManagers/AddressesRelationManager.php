<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\SiteSchema;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'street';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...AddressSchema::getSchema(),
                Repeater::make('addresses')
                    ->relationship('sites')
                    ->schema([
                        ...SiteSchema::getSchema()
                    ])
            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('street'),
                Tables\Columns\TextColumn::make('barangay.name'),
                Tables\Columns\TextColumn::make('city.name'),

                Tables\Columns\TextColumn::make('province.name'),
                Tables\Columns\TextColumn::make('region.name'),
                Tables\Columns\TextColumn::make('sites.label'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
