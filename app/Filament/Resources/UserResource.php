<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\LeavesRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : ""),
                Select::make('roles')->relationship('roles', 'name')->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('email_verified_at')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')->sortable(),

            ])
            ->actions([
                Impersonate::make('impersonate'),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')

                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')

                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LeavesRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
