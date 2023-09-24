<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Ramsey\Uuid\v1;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';


    protected static ?int $navigationSort = 70;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')->required(),
                Select::make('product_category_id')
                    ->default(function ($record) {
                        if ($record) {
                            return $record->product_category_id;
                        }
                        return null;
                    })
                    ->label('Category')
                    ->required()
                    ->relationship('productCategory', 'name')
                    ->createOptionForm([
                        TextInput::make('name')->required()
                    ])
                    ->reactive()
                    ->searchable(),
                TextInput::make('code')->required(),
                TextInput::make('unit')->required(),
                TextInput::make('available')
                    ->numeric()->required(),
                TextInput::make('low_threshold')
                    ->numeric()->required(),
                Textarea::make('description')->columnSpan(2)->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('name')->description(fn (Product $record) => $record->description),
                TextColumn::make('unit'),
                TextColumn::make('available'),
                TextColumn::make('low_threshold'),
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
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
