<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;


class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $recordTitleAttribute = 'body';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Checkbox::make('is_important'),
                Hidden::make('commented_by')->default(fn () => Auth()->user()->id),
                Forms\Components\Textarea::make('body')
                    ->hint('Enter freeform comments, concerns, reminders here')
                    ->rows(4)
                    ->label('Comments')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_important')->boolean(),
                Tables\Columns\TextColumn::make('body')->wrap(),
                TextColumn::make('created_at')->sortable(),
                TextColumn::make('commentedBy.name'),
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
