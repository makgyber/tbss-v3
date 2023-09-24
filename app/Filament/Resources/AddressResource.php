<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\SiteSchema;
use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Filament\Forms\Schemas\ClientSchema;
use App\Filament\Resources\AddressResource\RelationManagers\SitesRelationManager;
use Filament\Forms\Components\Placeholder;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    // protected static ?string $navigationGroup = 'Back Office';

    protected static ?int $navigationSort = 50;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client_id')
                    ->label('Client')
                    ->required()
                    ->relationship('client', 'name')
                    ->createOptionForm([
                        ...ClientSchema::getSchema()
                    ])
                    ->searchable(),
                ...AddressSchema::getSchema(),
                Repeater::make('sites')
                    ->relationship('sites')
                    ->schema(
                        SiteSchema::getSchema()
                    )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')->linkRecord(),
                Tables\Columns\TextColumn::make('region.name'),
                Tables\Columns\TextColumn::make('province.name'),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('barangay.name'),
                Tables\Columns\TextColumn::make('street'),

                Tables\Columns\TextColumn::make('sites.label'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SitesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }
}
