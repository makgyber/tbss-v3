<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Filament\Forms\Schemas\JobOrderSchema;
use App\Filament\Resources\JobOrderResource;
use App\Models\JobOrder;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
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
            ->columns([
                TextColumn::make('target_date')->date('Y-m-d H:i:s')->sortable(),
                TextColumn::make('job_order_type')->searchable()->sortable(),
                TextColumn::make('code'),
                Tables\Columns\TextColumn::make('summary')->wrap(),
                TextColumn::make('status'),
                TextColumn::make('createdBy.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->hidden(fn (HasRelationshipTable $livewire) => in_array($livewire->ownerRecord->status, ['expired', 'declined'])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (JobOrder $record): string => route('filament.resources.job-orders.edit', $record)),
                Tables\Actions\EditAction::make()
                    ->url(fn (JobOrder $record) => JobOrderResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'target_date';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }
}
