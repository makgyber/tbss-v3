<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogActivityResource\Pages;
use App\Filament\Resources\LogActivityResource\RelationManagers;
use App\Models\LogActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Activitylog\Models\Activity;

class LogActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationLabel = 'Log Activities';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $activeNavigationIcon = null;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('causer_type')
                    ->label(__('filament-spatie-activitylog::activity.causer_type'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
                Forms\Components\TextInput::make('causer_id')
                    ->label(__('filament-spatie-activitylog::activity.causer_id'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
                Forms\Components\TextInput::make('subject_type')
                    ->label(__('filament-spatie-activitylog::activity.subject_type'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
                Forms\Components\TextInput::make('subject_id')
                    ->label(__('filament-spatie-activitylog::activity.subject_id'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
                Forms\Components\TextInput::make('description')
                    ->label(__('filament-spatie-activitylog::activity.description'))->columnSpan(2),
                Forms\Components\KeyValue::make('properties.attributes')
                    ->label(__('filament-spatie-activitylog::activity.attributes'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
                Forms\Components\KeyValue::make('properties.old')
                    ->label(__('filament-spatie-activitylog::activity.old'))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 1
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject_type'),
                TextColumn::make('event'),
                TextColumn::make('causer.name')->label('Committed by')->searchable(),
                TextColumn::make('created_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLogActivities::route('/'),
            'create' => Pages\CreateLogActivity::route('/create'),
            'edit' => Pages\EditLogActivity::route('/{record}/edit'),
        ];
    }
}
