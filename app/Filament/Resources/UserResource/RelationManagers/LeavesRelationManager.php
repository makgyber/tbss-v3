<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavesRelationManager extends RelationManager
{
    protected static string $relationship = 'leaves';

    protected static ?string $recordTitleAttribute = 'type';

    protected static ?string $modelLabel = 'leave';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('leave_date')->required(),
                Select::make('type')
                    ->options(config('tbss.hr.user.leave_types'))
                    ->required(),
                Repeater::make('comments')
                    ->relationship('comments')
                    ->schema([
                        Textarea::make('body')
                            ->rows(4),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('leave_date')->date('Y-m-d'),
                Tables\Columns\TextColumn::make('type'),
                TextColumn::make('comments.body'),
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
