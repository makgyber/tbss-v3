<?php

namespace App\Filament\Resources\JobOrderResource\Pages;

use App\Filament\Forms\Schemas\JobOrderSchema;
use App\Filament\Resources\EntomResource;
use App\Filament\Resources\JobOrderResource;
use App\Filament\Resources\JobOrderResource\Widgets\ServiceHistory;
use App\Models\Address;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Entom;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Columns\Layout\Panel;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Forms\Flatpickr;
use Illuminate\Database\Eloquent\Factories\Relationship;

class EditJobOrder extends EditRecord
{
    protected static string $resource = JobOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('View parent entom')
                ->url(fn ($livewire) =>  EntomResource::getUrl('view', [$livewire->record->jobable_id])),
            // Actions\DeleteAction::make(),
        ];
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

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->reactive()
                    ->label('Client Name')->searchable()->required(),
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),

                Select::make('address_id')
                    ->relationship('address', 'street', function ($livewire, callable $get) {
                        $client_id = $get('client_id') ?: $livewire->record->jobable->client->id ?: $livewire->record->client_id;
                        $client = Client::find($client_id);

                        if ($client) {
                            return $client->addresses();
                        }
                    })
                    ->label('Address')
                    ->required()
                    ->reactive(),
                Select::make('sites')
                    ->relationship('sites', 'label')
                    ->multiple()
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
                    ->options(static::getTypeList()),

                MorphToSelect::make('jobable')
                    ->label("Select Contract or Entom to link")
                    ->types([
                        MorphToSelect\Type::make(Contract::class)
                            ->titleColumnName("code")
                            ->getOptionsUsing(function ($livewire, callable $get) {
                                $client_id = $get('client_id') ?: $livewire->record->client_id;
                                return Contract::where('client_id', $client_id)->pluck("code", "id");
                            }),
                        MorphToSelect\Type::make(Entom::class)
                            ->titleColumnName("client_name")
                            ->getOptionsUsing(function ($livewire) {
                                $entoms = Entom::where('client_id', $livewire->record->client_id)->get();
                                if (!$entoms) {
                                    return [];
                                }
                                $opts = [];
                                foreach ($entoms as $entom) {
                                    array_push($opts, ["client_name" => $entom->client->name, "id" => $entom->id]);
                                }
                                return collect($opts)->pluck("client_name", "id");
                            }),
                    ])->required(),
                Select::make('status')
                    ->options(config('tbss.job_order_status')),
                Flatpickr::make('target_date')
                    ->enableTime(true)->view('filament.forms.fields.flatpickr'),
                Textarea::make('summary')
                    ->rows(2)
                    ->columnSpan(1)
                    ->required(),
                Select::make('createdBy')
                    ->relationship('createdBy', 'name')
                    ->required(),
            ])->columns(2),
        ];

        // return JobOrderSchema::getSchema();
    }

    public function getTitle(): string
    {
        $clientName = $this->record->client->name;

        return 'Job Order for ' . $clientName .  ' at ' . $this->record->address->street . ', ' . $this->record->address->city->name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->record->serviceInterval;
    }

    protected function getFooterWidgets(): array
    {
        return [
            ServiceHistory::class,
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $newTargetDate = Carbon::make($data['target_date']);
        $oldTargetDate = Carbon::make($this->record['target_date']);
        $isSameDay = $newTargetDate->isSameDay($oldTargetDate);

        if (!$isSameDay && $data['status'] == 'scheduled') {
            $data['status'] = 'unscheduled';
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $changes = $this->record->getChanges();
        $hasTargetDate = array_key_exists('target_date', $changes);

        if ($hasTargetDate && $this->record['status'] == 'unscheduled') {
            $this->record->teams()->detach();
        }

        if (in_array($this->record['status'], ['postponed', 'cancelled'])) {
            $this->record->teams()->detach();
        }
    }
}
