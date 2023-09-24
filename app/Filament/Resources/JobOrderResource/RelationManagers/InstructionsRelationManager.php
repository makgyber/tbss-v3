<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstructionsRelationManager extends RelationManager
{
    protected static string $relationship = 'instructions';

    protected static ?string $recordTitleAttribute = 'instruction';

    public static function getTitleForRecord(Model $ownerRecord): string
    {

        return static::getTitle() . ' (' . $ownerRecord->{static::getRelationshipName()}->count() . ')';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('instruction')
                    ->rows(2)
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('instruction'),
                Tables\Columns\ToggleColumn::make('done'),
                Tables\Columns\TextColumn::make('done_by.name'),
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
            ])->defaultSort("updated_at", "desc");
    }
}
