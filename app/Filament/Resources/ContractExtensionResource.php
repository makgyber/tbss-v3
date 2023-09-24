<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractExtensionResource\Pages;
use App\Filament\Resources\ContractExtensionResource\RelationManagers;
use App\Models\ContractExtension;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractExtensionResource extends Resource
{
    protected static ?string $model = ContractExtension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    DatePicker::make('expired_on')->required(),
                    DatePicker::make('extended_to')->required(),
                    TextInput::make('visits')->numeric()->required(),
                ])->columns(3),
                Textarea::make('remarks'),
                Hidden::make('created_by')->default(fn () => auth()->user()->id)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract.code')->label('Parent Contract'),
                TextColumn::make('contract.client.name')->label('Client Name'),
                TextColumn::make('extended_to')->date()->toggleable()->sortable(),
                TextColumn::make('expired_on')->date()->toggleable()->sortable(),
                TextColumn::make('visits')->toggleable()->sortable(),
                TextColumn::make('remarks')->wrap()->toggleable()->sortable(),
                TextColumn::make('createdBy.name')->toggleable()->sortable(),
                TextColumn::make('created_at')->date()->toggleable()->sortable(),
                TextColumn::make('updated_at')->date()->toggleable()->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContractExtensions::route('/'),
            // 'create' => Pages\CreateContractExtension::route('/create'),
            'edit' => Pages\EditContractExtension::route('/{record}/edit'),
        ];
    }
}
