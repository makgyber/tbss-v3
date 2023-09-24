<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\ConcernResource\Pages;
use App\Filament\Resources\ConcernResource\RelationManagers;
use App\Filament\Resources\ConcernResource\RelationManagers\ResolutionsRelationManager;
use App\Models\Address;
use App\Models\Client;
use App\Models\Concern;
use App\Models\ConcernType;
use App\Models\Site;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class ConcernResource extends Resource
{
    protected static ?string $model = Concern::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Select::make('client_id')
                        ->label('Client')
                        ->relationship('client', 'name')
                        ->searchable()
                        ->required()
                        ->reactive(),
                    Select::make('address_id')
                        ->label('Address')
                        ->options(function (callable $get) {
                            $client = Client::find($get('client_id'));

                            if ($client) {
                                return $client->addresses()->pluck('street', 'id');
                            }
                        })
                        ->default(0)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                            $site = Site::where('address_id', $state)->first();
                            $set('site_id', $site->id);
                        }),

                    Select::make('site_id')
                        ->label('Site')
                        ->options(function (callable $get) {
                            $address = Address::find($get('address_id'));
                            if (!$address) {
                                return [];
                            }
                            return $address->sites()->pluck('label', 'id');
                        })
                        ->required()
                        ->reactive(),

                    Select::make('job_order_id')
                        ->label('Related Job Order')
                        ->options(function (callable $get) {
                            $client = Client::find($get('client_id'));
                            if (!$client) {
                                return [];
                            }
                            return $client->jobOrders->pluck('code', 'id');
                        })
                        ->reactive(),
                ])->columns(4)->columnSpan('full'),

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
                ])->columns(4)->columnSpanFull(),



                Textarea::make('summary')->columnSpanFull()->required(),
                Radio::make('urgency')->options([
                    'low' => 'low',
                    'medium' => 'medium',
                    'high' => 'high',
                ])->inline()->required()->default('low'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('urgency')->sortable()->toggleable()
                    ->colors(['danger' => 'high',  'success' => 'low', 'warning' => 'medium']),
                BadgeColumn::make('status')->sortable()->toggleable()
                    ->colors(['danger' => 'pending',  'primary' => 'closed']),
                BadgeColumn::make('type')->label('Response Type')->toggleable()
                    ->colors(['success' => 'proactive', 'warning' => 'reactive']),
                TextColumn::make('concern_type')->toggleable()->sortable(),
                TextColumn::make('created_at')->toggleable()->sortable()->date()
                    ->description(fn ($record) => Carbon::today("Asia/Manila")->diffInDays(Carbon::make($record->created_at)->setTime(0, 0))),
                TextColumn::make('reported_date')->toggleable()->sortable()->date()
                    ->description(fn ($record) => Carbon::today("Asia/Manila")->diffInDays(Carbon::make($record->reported_date))),
                TextColumn::make('resolutionInterval')->toggleable()
                    ->label('Aging')
                    ->default(function ($record) {
                        if ($record->status === 'pending') {
                            return  Carbon::today("Asia/Manila")->diffInDays(Carbon::make($record->reported_date));
                        } else {
                            return $record->resolutionInterval;
                        }
                    }),
                TextColumn::make('jobOrder.code')->label('Related JO')->toggleable()->wrap()
                    ->url(function ($record) {
                        if ($record->job_order_id) {
                            return JobOrderResource::getUrl('edit', ['record' => $record->job_order_id]);
                        }
                        return null;
                    }),
                TextColumn::make('client.name')->toggleable()->wrap(),
                TextColumn::make('address.street')->toggleable()->wrap(),
                TextColumn::make('site.label')->toggleable()->wrap(),
                ViewColumn::make('client.contact_information')->toggleable()->label('Contact Information')
                    ->view('filament.tables.columns.client-contact-information'),
                TextColumn::make('summary')->toggleable()
                    ->wrap(),
                TextColumn::make('assignedTo.name')->toggleable()->wrap(),
                TextColumn::make('lastActivity.details')
                    ->label('Last Activity Details')->toggleable()
                    ->wrap(),
                TextColumn::make('lastActivity.assignedTo.name')
                    ->label('Last Actioned By')->toggleable()->wrap(),
                TextColumn::make('lastActivity.created_at')
                    ->label('Last Action Date')->toggleable()->wrap()->date(),
                TextColumn::make('createdBy.name')->toggleable()->wrap()
            ])
            ->filters([
                SelectFilter::make('urgency')
                    ->options([
                        'low' => 'low',
                        'medium' => 'medium',
                        'high' => 'high',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'closed' => 'closed',
                    ]),
                SelectFilter::make('type')
                    ->label('Action Type')
                    ->options([
                        'proactive' => 'proactive',
                        'reactive' => 'reactive',
                    ]),
                SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable(),
                SelectFilter::make('assignedTo')
                    ->relationship('assignedTo', 'name')
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])->columns(2)->columnSpan(2)
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // FilamentExportBulkAction::make('export'),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export')
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ResolutionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConcerns::route('/'),
            'create' => Pages\CreateConcern::route('/create'),
            'edit' => Pages\EditConcern::route('/{record}/edit'),
        ];
    }
}
