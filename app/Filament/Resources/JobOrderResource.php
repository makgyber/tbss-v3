<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Forms\Schemas\JobOrderSchema;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\JobOrderResource\Pages;
use App\Filament\Resources\JobOrderResource\RelationManagers\InstructionsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\FindingsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\RecommendationsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\TreatmentsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\JobOrderResource\Widgets\ServiceHistory;
use App\Filament\Resources\LeadResource\RelationManagers\CommentsRelationManager;
use App\Models\Address;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Entom;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\Site;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use App\Filament\Forms\Flatpickr;
use App\Filament\Resources\JobOrderResource\RelationManagers\ConcernsRelationManager;

class JobOrderResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 30;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    private static function getTypeList()
    {
        $types = JobOrderType::all();
        $list = [];
        foreach ($types as $type) {
            $list[$type->name] = $type->name;
        }
        return $list;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        if ($state) {
                            $address = Address::where('client_id', $state)->first();
                            $set('address_id', $address->id);
                            $set('site_id', $address->sites->first()->id);
                        } else {
                            $set('address_id', null);
                            $set('site_id', null);
                        }
                    }),
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true),
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
                Select::make('job_order_type')
                    ->required()
                    ->searchable()
                    ->options(static::getTypeList())
                    ->default(Arr::first(static::getTypeList())),
                MorphToSelect::make('jobable')
                    ->label("Select Contract or Entom to link")
                    ->reactive()
                    ->types([
                        MorphToSelect\Type::make(Contract::class)
                            ->titleColumnName("code")
                            ->getOptionsUsing(function (callable $get) {
                                $contracts = Contract::where("client_id", $get("client_id"))
                                    ->whereNotIn('status', ['expired', 'declined'])->get();
                                if (!$contracts) {
                                    return [];
                                }
                                return $contracts->pluck("code", "id");
                            }),
                        MorphToSelect\Type::make(Entom::class)
                            ->titleColumnName("client_requests")
                            ->getOptionsUsing(function (callable $get) {
                                $entoms = Entom::where("client_id", $get("client_id"))->get();
                                if (!$entoms) {
                                    return [];
                                }
                                $opts = [];
                                foreach ($entoms as $entom) {
                                    array_push($opts, ["client_name" => $entom->client->name, "id" => $entom->id]);
                                }
                                return collect($opts)->pluck("client_name", "id");
                            })
                            ->getOptionLabelFromRecordUsing(fn (Entom $record): string => "{$record->client->name}"),
                    ])->required(),
                Select::make('status')
                    ->required()
                    ->options(config('tbss.job_order_status'))
                    ->default(Arr::first(config('tbss.job_order_status'))),

                Flatpickr::make('target_date')->default(now())
                    ->enableTime(true)
                    ->altFormat('F j, Y h:i K'),

                Textarea::make('summary')
                    ->rows(2)
                    ->required(),
                Select::make('createdBy')
                    ->relationship('createdBy', 'name')
                    ->default(fn () => auth()->user()->id)
                    ->required()
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('target_date')->date('M j, Y g:i a')->sortable()->wrap()->description(fn ($record) => $record->job_order_type)->toggleable(),
                TextColumn::make('address.client.name')->searchable()->wrap()->toggleable(),
                TextColumn::make('address.street')->searchable()->wrap()->toggleable(),
                TextColumn::make('code')->searchable()->wrap()->toggleable(),
                TextColumn::make('status')->sortable()->toggleable(),
                TextColumn::make('jobable_type')
                    ->url(function ($record) {
                        if ($record->jobable_type == '') {
                            return null;
                        }
                        $resource = str_replace('Models', 'Filament\\Resources', $record->jobable_type) . 'Resource';
                        return $resource::getUrl('view', ['record' => $record->jobable_id]);
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->jobable_type == '') {
                            return null;
                        }

                        $client = $record->jobable_type::find($record->jobable_id)->client->name;
                        $model = str_replace('App\\Models\\', '', $record->jobable_type);

                        return "$model : $client";
                    })
                    ->label('Source')
                    ->wrap()->size('sm')->toggleable(),
                TextColumn::make('summary')->wrap()->size('sm')->toggleable(),
                TextColumn::make('technicians')->formatStateUsing(function ($record) {
                    $tm = [];
                    $team = $record->teams->first();
                    if (is_null($team)) {
                        return '';
                    }
                    foreach ($team->users as $user) {
                        array_push($tm, $user->name);
                    };
                    return implode(', ', $tm);
                })->wrap()->toggleable(),
                TextColumn::make('findings_count')->label('Fndgs')->sortable()->toggleable(),
                TextColumn::make('recommendations_count')->label('Recos')->sortable()->toggleable(),
                TextColumn::make('createdBy.name')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(config('tbss.job_order_status')),
                Filter::make('target_date')
                    ->form([
                        Forms\Components\DatePicker::make('target_from'),
                        Forms\Components\DatePicker::make('target_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(
                                $data['target_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '>=', $date),
                            )
                            ->when(
                                $data['target_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '<=', $date),
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
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            InstructionsRelationManager::class,
            ProductsRelationManager::class,
            FindingsRelationManager::class,
            RecommendationsRelationManager::class,
            TreatmentsRelationManager::class,
            ActivitiesRelationManager::class,
            CommentsRelationManager::class,
            ConcernsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobOrders::route('/'),
            'create' => Pages\CreateJobOrder::route('/create'),
            'edit' => Pages\EditJobOrder::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->address->client?->name;
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return JobOrderResource::getUrl('edit', ['record' => $record]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'address.street'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Code' => $record->code,
            'Address' => $record->address->street,
        ];
    }
}
