<?php

namespace App\Filament\Resources\EntomResource\RelationManagers;

use App\Filament\Forms\Schemas\JobOrderSchema;
use App\Filament\Tables\Columns\JobOrderColumns;
use App\Models\Entom;
use App\Models\JobOrder;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'jobOrders';

    protected static ?string $recordTitleAttribute = 'instruction';
    protected $listeners = ['refresh' => '$refresh'];
    public function form(Form $form): Form
    {

        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(JobOrderColumns::getColumns())
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form(JobOrderSchema::getSchema())
                    ->mutateFormDataUsing(function (HasRelationshipTable $livewire, array $data): array {
                        $data['entom_id'] = $livewire->ownerRecord->id;
                        $data['created_by'] = auth()->user()->id;
                        return $data;
                    })
                    ->using(function (RelationManager $livewire, array $data): Model {
                        $jobOrder = $livewire->getRelationship()->create($data);
                        return $jobOrder;
                    })
                    ->after(function ($livewire) {
                        // Runs after the form fields are saved to the database.
                        $livewire->emit('refresh');
                    })
                    ->resetFormData(),
                // Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (JobOrder $record): string => route('filament.resources.job-orders.edit', $record)),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DetachBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
