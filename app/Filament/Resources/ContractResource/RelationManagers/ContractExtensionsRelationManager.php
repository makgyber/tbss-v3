<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractExtensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'contractExtensions';

    protected static ?string $recordTitleAttribute = 'remarks';

    public function form(Form $form): Form
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
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('extended_to', 'desc');
    }
}
