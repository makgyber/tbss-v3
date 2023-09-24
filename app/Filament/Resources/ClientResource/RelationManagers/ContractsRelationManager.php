<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Forms\Schemas\ContractSchema;
use App\Filament\Resources\ContractResource;
use App\Filament\Tables\Columns\ContractColumns;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractsRelationManager extends RelationManager
{
    protected static string $relationship = 'contracts';

    protected static ?string $recordTitleAttribute = 'code';

    public function form(Form $form): Form
    {
        $hideClient = true;
        return $form
            ->schema(ContractSchema::getSchema($hideClient));
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(ContractColumns::getColumns())
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Contract $record) => ContractResource::getUrl('view', [$record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
