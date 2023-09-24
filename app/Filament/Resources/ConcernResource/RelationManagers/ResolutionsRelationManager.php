<?php

namespace App\Filament\Resources\ConcernResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResolutionsRelationManager extends RelationManager
{
    protected static string $relationship = 'resolutions';

    protected static ?string $recordTitleAttribute = 'details';

    protected static ?string $title = 'Resolution Activities';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Checkbox::make('closed')->label('Close concern'),
                Select::make('assigned_to')
                    ->label('Assigned To')
                    ->relationship('assignedTo', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->default(function ($livewire) {
                        return $livewire->ownerRecord->assignedTo->id;
                    }),
                Textarea::make('details')
                    ->required()
                    ->maxLength(255),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('closed')
                    ->label('Closed')
                    ->getStateUsing(fn ($record) => (bool)  $record->closed)
                    ->options([
                        'heroicon-o-x-circle',
                        'heroicon-o-check-circle' => fn ($state): bool => $state,
                    ])
                    ->colors([
                        'danger',
                        'success' => fn ($state): bool => $state
                    ])->toggleable(),
                TextColumn::make('created_at')->toggleable()
                    ->label('Date')->dateTime('F j, Y h:i a'),
                TextColumn::make('details')->toggleable()->wrap()->searchable(),
                TextColumn::make('assignedTo.name')->toggleable()->searchable(),
                TextColumn::make('createdBy.name')->toggleable(),

            ])
            ->filters([
                TernaryFilter::make('closed')->label('Is closed?'),
                SelectFilter::make('assignedTo')
                    ->relationship('assignedTo', 'name')
                    ->searchable(),
                SelectFilter::make('createdBy')
                    ->relationship('createdBy', 'name')
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->user()->id;
                        return $data;
                    })
                    ->using(function (HasRelationshipTable $livewire, array $data) {

                        if ($data['closed']) {
                            $livewire->ownerRecord->status = 'closed';
                            $livewire->ownerRecord->save();
                        }
                        return $livewire->getRelationship()->create($data);
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getTableHeading(): Htmlable|string|null
    {
        return static::getTitle() . ' | Status: ' . $this->ownerRecord->status;
    }
}
