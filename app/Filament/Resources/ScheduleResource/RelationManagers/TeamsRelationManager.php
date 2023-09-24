<?php

namespace App\Filament\Resources\ScheduleResource\RelationManagers;

use App\Filament\Forms\Schemas\TeamSchema;
use App\Filament\Resources\ScheduleResource;
use App\Models\JobOrder;
use App\Models\Leave;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Livewire;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    protected static ?string $recordTitleAttribute = 'code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                TextColumn::make('users.name')
                    ->label('Technicians')->wrap(),
                TextColumn::make('jobOrders.code')->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form(TeamSchema::getSchema()),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(TeamSchema::getSchema()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-s-printer')
                    ->color('success')
                    ->url(fn ($record) => ScheduleResource::getUrl('team_schedule_details', [$record]))
                    ->openUrlInNewTab(true),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-s-printer')
                    ->color('warning')
                    ->url(fn ($record) => ScheduleResource::getUrl('team_schedule_details', [$record, 'print' => 1]))
                    ->openUrlInNewTab(true),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort("updated_at", "desc");
    }
}
