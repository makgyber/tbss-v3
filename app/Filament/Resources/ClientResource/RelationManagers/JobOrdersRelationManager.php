<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Forms\Schemas\JobOrderSchema;
use App\Filament\Tables\Columns\JobOrderColumns;
use App\Models\JobOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'jobOrders';

    protected static ?string $recordTitleAttribute = 'code';

    public function form(Form $form): Form
    {
        return $form
            ->schema(JobOrderSchema::getSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(JobOrderColumns::getColumns())
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (JobOrder $record): string => route('filament.resources.job-orders.edit', $record)),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
