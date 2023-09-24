<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use App\Filament\Resources\ConcernResource;
use App\Models\Concern;
use App\Models\ConcernType;
use App\Models\Resolution;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class ConcernsRelationManager extends RelationManager
{
    protected static string $relationship = 'concerns';

    protected static ?string $recordTitleAttribute = 'summary';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make([
                    Select::make('type')->label('Response Type')->required()
                        ->options(fn () => ['proactive' => 'proactive', 'reactive' => 'reactive']),
                    Select::make('concern_type')
                        ->required()
                        ->searchable()
                        ->options(static::getTypeList())
                        ->default(Arr::first(static::getTypeList())),
                    Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('assignedTo', 'name')
                        ->searchable()
                        ->required()
                        ->reactive(),
                    DatePicker::make('reported_date')->default(now())->required(),
                ])->columns(2)->columnSpanFull(),

                Textarea::make('summary')->columnSpanFull()->required(),
                Hidden::make('client_id')->default(function (HasRelationshipTable $livewire) {
                    return $livewire->ownerRecord->client_id;
                }),
                Hidden::make('address_id')->default(function (HasRelationshipTable $livewire) {
                    return $livewire->ownerRecord->address_id;
                }),
                Hidden::make('site_id')->default(function (HasRelationshipTable $livewire) {
                    return $livewire->ownerRecord->site_id;
                })
            ]);
    }

    private static function getTypeList()
    {
        $types = ConcernType::all();
        $list = [];
        foreach ($types as $type) {
            $list[$type->name] = $type->name;
        }
        return $list;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('status')->sortable()->toggleable()
                    ->colors(['danger' => 'pending',  'primary' => 'closed']),
                BadgeColumn::make('type')->label('Response Type')->toggleable()
                    ->colors(['success' => 'proactive', 'warning' => 'reactive']),
                TextColumn::make('concern_type')->toggleable()->sortable(),
                TextColumn::make('reported_date')->toggleable()->sortable(),
                TextColumn::make('resolutionInterval')->toggleable()
                    ->label('Time to Resolution')
                    ->default(function ($record) {
                        if ($record->status === 'pending') {
                            return  Carbon::now()->shortAbsoluteDiffForHumans(Carbon::make($record->openedDate));
                        } else {
                            return $record->resolutionInterval;
                        }
                    }),
                // TextColumn::make('client.name')->toggleable()->wrap(),
                // TextColumn::make('address.street')->toggleable()->wrap(),
                // TextColumn::make('site.label')->toggleable()->wrap(),
                TextColumn::make('summary')->toggleable()
                    ->wrap(),
                TextColumn::make('assignedTo.name')->toggleable()->wrap(),
                TextColumn::make('lastActivity.details')
                    ->label('Last Activity Details')->toggleable()
                    ->wrap(),
                TextColumn::make('lastActivity.assignedTo.name')
                    ->label('Last Actioned By')->toggleable()->wrap(),
                TextColumn::make('createdBy.name')->toggleable()->wrap()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['status'] = 'pending';
                        $data['created_by'] = auth()->user()->id;
                        return $data;
                    })
                    ->using(function (HasRelationshipTable $livewire, array $data) {
                        $model = $livewire->getRelationship()->create($data);

                        Resolution::create([
                            'concern_id' => $model->id,
                            'closed' => false,
                            'details' => 'concern initiated',
                            'created_by' => $data['created_by'],
                            'assigned_to' => $data['assigned_to'],
                        ]);

                        return $model;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => ConcernResource::getUrl('edit', ['record' => $record])),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }
}
