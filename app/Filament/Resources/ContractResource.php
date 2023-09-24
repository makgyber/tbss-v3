<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Forms\Schemas\ContractSchema;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Filament\Resources\ContractResource\RelationManagers\ContractExtensionsRelationManager;
use App\Filament\Resources\ContractResource\RelationManagers\JobOrdersRelationManager;
use App\Filament\Resources\ContractResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\ContractResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\LeadResource\RelationManagers\CommentsRelationManager;
use App\Filament\Tables\Columns\ContractColumns;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Entom;
use App\Models\JobOrder;
use App\Models\Lead;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ContractSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ContractColumns::getColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('link_jo')
                    ->label('Link JO')
                    ->action(function (Contract $record, array $data) {
                        $jolist = [];
                        foreach ($data['jobOrders'] as $jo) {
                            array_push($jolist, JobOrder::find($jo));
                        }
                        $record->jobOrders()->saveMany($jolist);
                    })
                    ->form([
                        CheckboxList::make('jobOrders')
                            ->options(function (Contract $record) {
                                return JobOrder::where('client_id', $record->client_id)
                                    ->where('jobable_id', '')->pluck('code', 'id');
                            }),
                    ]),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // FilamentExportBulkAction::make('export'),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export')
            ])
            ->defaultSort("updated_at", "desc");
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
            ProductsRelationManager::class,
            JobOrdersRelationManager::class,
            PaymentsRelationManager::class,
            ContractExtensionsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
            'view' => Pages\ViewContract::route('/{record}'),
        ];
    }
}
