<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\ContractsRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\JobOrdersRelationManager;
use App\Filament\Resources\LeadResource\RelationManagers\CommentsRelationManager;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class ClientResource extends Resource
{

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // protected static ?string $navigationGroup = 'Back Office';

    protected static ?int $navigationSort = 40;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('classification')->sortable()
                    ->colors([
                        'primary' => 'A',
                        'secondary' => 'B',
                        'warning' => 'C',
                        'success' => 'D',
                        'danger' => 'E',
                    ]),
                TextColumn::make('name')->sortable()->searchable(),
                ViewColumn::make('contact_information')
                    ->view('filament.tables.columns.client-contact-information'),
                ViewColumn::make('addresses')
                    ->view('filament.tables.columns.client-address'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(config('tbss.client_classification')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            CommentsRelationManager::class,
            ContractsRelationManager::class,
            JobOrdersRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/{record}'),
            // 'service-history' => Pages\ServiceHistory::route('/{record}/service-history')
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ClientResource::getUrl('view', ['record' => $record]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'addresses.street'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Address' => $record->fullAddress(),
        ];
    }
}
