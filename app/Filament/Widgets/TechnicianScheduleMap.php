<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FindingResource;
use App\Filament\Tables\Columns\JobOrderColumns;
use App\Models\Finding;
use App\Models\JobOrder;
use App\Models\PestType;
use App\Models\Recommendation;
use App\Models\Schedule;
use App\Models\ServiceType;
use App\Models\Team;
use App\Models\Tracker;
use App\Models\Treatment;
use App\Models\TreatmentType;
use App\Policies\FindingPolicy;
use Carbon\Carbon;
use Cheesegrits\FilamentGoogleMaps\Helpers\MapsHelper;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Livewire\TemporaryUploadedFile;

class TechnicianScheduleMap extends MapTableWidget
{
    protected static ?string $pollingInterval = '30s';

    protected array $mapConfig = [
        'draggable' => false,
        'center'    => [
            'lat' => 14.3419776,
            'lng' => 121.2171392,
        ],
        'zoom'       => 8,
        'fit'        => true,
        'gmaps'      => '',
        'clustering' => true,
    ];


    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Scheduled Job Orders';

    protected static ?bool $clustering = true;

    protected static ?int $zoom = 12;

    protected $selectedDate;

    public function getMapConfig(): string
    {
        return json_encode(
            array_merge($this->mapConfig, [
                'clustering' => self::getClustering(),
                'layers'     => $this->getLayers(),
                'zoom'       => $this->getZoom(),
                'controls'   => $this->controls,
                'fit'        => $this->getFitToBounds(),
                'gmaps'      => MapsHelper::mapsUrl() ?? '',
            ])
        );
    }

    protected function getTracker($teamId): array
    {
        $trackers = Tracker::whereDate('created_at', Carbon::now())
            ->where("team_id", $teamId)->get();

        $data = [];

        foreach ($trackers as $tracker) {

            $data[] = [
                'location'  => [
                    'lat' => $tracker->lat ? round(floatval($tracker->lat), static::$precision) : 0,
                    'lng' => $tracker->lng ? round(floatval($tracker->lng), static::$precision) : 0,
                ],

                'label'     => $tracker->team->code,

                'icon' => [
                    'url' => asset('/storage/images/car-svgrepo-com.svg'),
                    'type' => 'svg',
                    'scale' => [35, 35],
                ],
            ];
        }
        return $data;
    }

    protected function getData(): array
    {
        $data = [];
        $teamId = "";

        $jobOrders = $this->getRecords();
        foreach ($jobOrders as $jo) {
            if ($jo->address->latitude && $jo->address->longitude) {
                $name = ($jo->client) ? $jo->client->name : $jo->jobable->client->name;
                $data[] = [
                    'location'  => [
                        'lat' => floatVal($jo->address->latitude),
                        'lng' => floatVal($jo->address->longitude),
                    ],
                    'label' => $jo->teams . $jo->code . ' ' . $name . ' ' . $jo->address->street,
                    'icon' => [
                        'url' => asset('/storage/images/home-selected-state-svgrepo-com.svg'), //url('images/home-selected-state-svgrepo-com.svg'),
                        'type' => 'svg',
                        'scale' => [35, 35],
                    ],
                ];

                $teamId = $jo->teams[0]->id;
            }
        }
        $trackers = $this->getTracker($teamId);

        if (!empty($trackers)) {
            array_push($data, $trackers[0]);
        }

        return $data;
    }

    protected function getTableQuery(): Builder
    {
        return JobOrder::whereHas('teams', function ($q) {
            $q->whereHas('users', function ($q2) {
                $q2->where('user_id', auth()->user()->id);
            });
        });
    }

    protected function getTableColumns(): array
    {
        return [
            Split::make(
                [
                    TextColumn::make('target_date')->date('h:i a')->size('sm'),
                    TextColumn::make('status')->alignEnd()->badge()
                        ->colors([
                            'warning' => 'scheduled',
                            'danger' => 'postponed',
                            'success' => 'serviced',
                            'secondary' => 'cancelled',
                        ])->size('xs'),

                ]
            ),
            Split::make(
                [
                    TextColumn::make('code')->size('sm')->description(fn ($record) => $record->serviceInterval),
                    TextColumn::make('job_order_type')->size('sm')->alignEnd(),
                ]
            ),
            Panel::make([

                TextColumn::make('address.client.name')->wrap()->size('sm'),
                TextColumn::make('address.street')->wrap()->size('sm'),
                TextColumn::make('summary')->size('sm'),

                ViewColumn::make('findings')
                    ->view('filament.resources.common.tech-findings-list'),
                ViewColumn::make('recommendations')
                    ->view('filament.resources.common.tech-recommendations-list'),
                ViewColumn::make('treatments')
                    ->view('filament.resources.common.tech-treatments-list'),
            ])->collapsed(true)

        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, 200, 500];
    }

    protected function getTableFilters(): array
    {

        return [
            Filter::make('target_date')
                ->form([
                    DatePicker::make('from')->label('Service Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['from']) {
                        return $query->whereDate('target_date', $data['from']);
                    } else {
                        return $query->whereDate('target_date',  Carbon::now());
                    }
                })

                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['from'] ?? null) {
                        $indicators['from'] = 'Service Date: ' . Carbon::parse($data['from'])->toFormattedDateString();
                    }

                    return $indicators;
                })

        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('status')
                ->label('Status')
                ->button()->color('primary')
                ->action(function (JobOrder $record, array $data) {
                    $record->status = $data['status'];
                    $record->save();
                    if ($data['comment']) {
                        $record->comments()->create([
                            'body' => $data['comment'],
                            'commented_by' => auth()->user()->id,
                        ]);
                    }
                })
                ->form([
                    Select::make('status')
                        ->label('New status')
                        ->options([
                            'started' => 'started',
                            'completed' => 'completed',
                            'postponed' => 'postponed',
                            'cancelled' => 'cancelled',
                        ]),
                    Textarea::make('comment'),
                ]),
            Action::make('finding')
                ->label('Finding')
                ->button()->color('success')
                ->action(fn () => 'create-finding')
                ->url(fn (JobOrder $record) => FindingResource::getUrl("create", ["job_order_id" => $record->id]))
                ->form([
                    Card::make()
                        ->schema([
                            TextInput::make('location')
                                ->datalist([
                                    'bedroom',
                                    'kitchen',
                                    'garden',
                                    'master bedroom',
                                    'toilet',
                                    'bathroom',
                                    '1st floor',
                                    '2nd floor',
                                ])
                                ->required()
                                ->maxLength(255),
                            TextInput::make('infestation')
                                ->datalist(function () {
                                    $types = PestType::all();
                                    $list = [];
                                    foreach ($types as $type) {
                                        $list[$type->name] = $type->name;
                                    }
                                    return $list;
                                })
                                ->required()
                                ->maxLength(255),
                            Select::make('degree')
                                ->options(config('tbss.infestation_degree'))
                                ->required(),
                        ])->columns(3),

                    RichEditor::make('remarks')
                        ->disableToolbarButtons(['attachFiles'])
                        ->required()
                        ->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->saveRelationshipsUsing(function (JobOrder $record) {
                            $finding = Finding::create($this->mountedTableActionData);
                            $record->findings()->save($finding);

                            $attachments = Arr::flatten($this->mountedTableActionData["attachments"]);

                            foreach ($attachments as $attach) {
                                $change = ['attributes' => ['image' => 1, 'uploaded_at' => now()], 'old' => []];
                                activity()
                                    ->event('image uploaded')
                                    ->causedBy(auth()->user()->id)
                                    ->withProperties($change)
                                    ->performedOn($finding)
                                    ->log('finding image uploaded');
                                activity()
                                    ->event('image uploaded')
                                    ->causedBy(auth()->user()->id)
                                    ->withProperties($change)
                                    ->performedOn($finding->jobOrder)
                                    ->log('finding image uploaded');
                                $finding->addMedia($attach)
                                    ->toMediaCollection("attachedfindings");
                            }
                        })
                        ->collection('attachedfindings')
                        ->multiple()
                        // ->enableDownload()
                        // ->enableOpen()
                        ->preserveFilenames()
                        ->responsiveImages()->enableReordering()
                        ->columnSpanFull(),
                    Hidden::make('noted_by')
                        ->default(Auth()->user()->id),
                ]),

            Action::make('recommendation')
                ->label('Reco')
                ->button()->color('danger')
                ->action(function (JobOrder $record, array $data) {
                    // $record->recommendations()->create($data);
                })
                ->form([
                    TextInput::make('service_type')
                        ->datalist(function () {
                            $types = ServiceType::all();
                            $list = [];
                            foreach ($types as $type) {
                                $list[$type->name] = $type->name;
                            }
                            return $list;
                        })->columnSpan(2)
                        ->required(),
                    Select::make('priority')
                        ->options(config('tbss.priority'))
                        ->required()
                        ->columnSpan(2),
                    Textarea::make('description')
                        ->required()
                        ->rows(3)
                        ->columnSpan(2),
                    SpatieMediaLibraryFileUpload::make('recommendations')->label('Attachments')
                        ->saveRelationshipsUsing(function (JobOrder $record) {

                            $attachments = Arr::flatten($this->mountedTableActionData["recommendations"]);
                            $recommendation = Recommendation::create($this->mountedTableActionData);

                            if ($attachments) {
                                $file = is_array($attachments) ? $attachments[0] : $attachments;

                                $recommendation->addMedia($file)
                                    ->toMediaCollection("attachedrecommendations");
                            }

                            $record->recommendations()->save($recommendation);
                        })
                        ->collection('attachedrecommendations')
                        ->multiple()
                        ->enableDownload()
                        ->enableOpen()
                        ->preserveFilenames()
                        ->responsiveImages()->enableReordering()
                        ->columnSpan(2),
                    Hidden::make('recommended_by')
                        ->default(Auth()->user()->id),
                ]),

            Action::make('treatment')
                ->label('Treatment')
                ->button()->color('danger')
                ->action(function (JobOrder $record, array $data) {
                    // $record->recommendations()->create($data);
                })
                ->form([
                    TextInput::make('treatment_type')
                        ->datalist(function () {
                            $types = TreatmentType::all();
                            $list = [];
                            foreach ($types as $type) {
                                $list[$type->name] = $type->name;
                            }
                            return $list;
                        })
                        ->required()
                        ->maxLength(255),
                    TextInput::make('location')
                        ->datalist([
                            'bedroom',
                            'kitchen',
                            'garden',
                            'master bedroom',
                            'toilet',
                            'bathroom',
                            '1st floor',
                            '2nd floor',
                        ])
                        ->required()
                        ->maxLength(255),
                    TextInput::make('quantity'),
                    SpatieMediaLibraryFileUpload::make('treatments')->label('Attachments')
                        ->saveRelationshipsUsing(function (JobOrder $record) {

                            $attachments = Arr::flatten($this->mountedTableActionData["treatments"]);
                            $treatment = new Treatment([
                                "treatment_type" => $this->mountedTableActionData["treatment_type"],
                                "location" => $this->mountedTableActionData["location"],
                                "quantity" => $this->mountedTableActionData["quantity"] ?: 0,
                                "user_id" => $this->mountedTableActionData["user_id"] ?: 0,
                            ]);

                            // $record->treatments()->save($treatment);

                            if ($attachments) {
                                $file = is_array($attachments) ? $attachments[0] : $attachments;

                                $treatment->addMedia($file)
                                    ->toMediaCollection("attachedtreatments");
                            }

                            $record->treatments()->save($treatment);
                        })
                        ->collection('attachedtreatments')
                        ->multiple()
                        ->enableDownload()
                        ->enableOpen()
                        ->preserveFilenames()
                        ->responsiveImages()->enableReordering()
                        ->columnSpan(2),
                    Hidden::make('user_id')
                        ->default(Auth()->user()->id),
                ]),
        ];
    }
}
