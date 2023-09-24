<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentTypeResource\Pages;
use App\Filament\Resources\TreatmentTypeResource\RelationManagers;
use App\Models\TreatmentType;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentTypeResource extends Resource
{
    protected static ?string $model = TreatmentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Type Dictionary';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("name")->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([TextColumn::make('name')->sortable()->searchable()])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTreatmentTypes::route('/'),
        ];
    }
}
