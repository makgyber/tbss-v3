<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOrderTypeResource\Pages;
use App\Filament\Resources\JobOrderTypeResource\RelationManagers;
use App\Models\ContractType;
use App\Models\JobOrderType;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobOrderTypeResource extends Resource
{
    protected static ?string $model = JobOrderType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Type Dictionary';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("code")->required(),
                TextInput::make('name')->required(),
                Select::make('instruction_list')
                    ->multiple()
                    ->options(function () {
                        $list = Option::where("list", "instructions")->get()->pluck("value", "value");
                        return $list;
                    }),
                Select::make('contract_type_list')
                    ->multiple()
                    ->options(function () {
                        $contractTypes = ContractType::all()->pluck("name", "name");
                        return $contractTypes;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("code")->searchable()->sortable(),
                TextColumn::make("name")->searchable()->sortable(),
                TagsColumn::make("instruction_list"),
                TagsColumn::make("contract_type_list"),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ManageJobOrderTypes::route('/'),
        ];
    }
}
