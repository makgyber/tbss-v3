<?php

namespace App\Filament\Resources\ContractExtensionResource\Widgets;

use App\Models\ContractExtension;
use Filament\Widgets\Widget;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class ClientProfile extends Widget implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?ContractExtension $record = null;

    protected static string $view = 'filament.resources.contract-extension-resource.widgets.client-profile';

    protected function getViewData(): array
    {
        return ['client' => $this->record->contract->client];
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(4)->schema([
                Section::make('Parent Contract Details')
                    ->schema([
                        Section::make('Info')
                            ->schema([
                                Placeholder::make('code')->content($this->record->contract->code),
                                Placeholder::make('contractType')->content($this->record->contract->contract_type),
                                Placeholder::make('address')->content(function () {
                                    return view(
                                        'filament.resources.common.addresses',
                                        ['addresses' => $this->record->contract->addresses]
                                    );
                                }),
                            ])->columnSpan(1)->inlineLabel()->compact(),
                        Section::make('Scheduling')
                            ->schema([
                                Placeholder::make('start')->content($this->record->contract->start),
                                Placeholder::make('end')->content($this->record->contract->end),
                                Placeholder::make('visits')->content($this->record->contract->visits),
                                Placeholder::make('frequency')->content($this->record->contract->frequency),

                            ])->columnSpan(1)->inlineLabel()->compact(),
                        Section::make('Payments')
                            ->schema([
                                Placeholder::make('contractPrice')->content($this->record->contract->contract_price),
                                Placeholder::make('paymentTerms')->content($this->record->contract->payment_terms),
                                Placeholder::make('paymentStatus')->content($this->record->contract->payment_status),
                                Placeholder::make('visits')->content($this->record->contract->visits),
                            ])->columnSpan(1)->inlineLabel()->compact(),
                        Section::make('Admin')
                            ->schema([
                                Placeholder::make('status')->content($this->record->contract->status),
                                Placeholder::make('created_at')->content($this->record->contract->created_at),
                                Placeholder::make('updated_at')->content($this->record->contract->updated_at),
                                Placeholder::make('assignedTo')->content($this->record->contract->assignedTo->name),
                            ])->columnSpan(1)->inlineLabel()->compact(),

                        Section::make('Job Order History')
                            ->compact()
                            ->collapsible()
                            ->schema($this->getJobOrders($this->record->contract->jobOrders)),

                        Section::make('Payments')->compact(),
                    ])
                    ->columns(4)
                    ->collapsed(false)
                    ->compact()
                    ->columnSpan(3),
                Section::make($this->record->contract->client->name)
                    ->schema([
                        Placeholder::make('classification')->content($this->record->contract->client->classification),
                        Placeholder::make('contact_information')->content(function () {
                            return view(
                                'filament.resources.common.contact-information',
                                ['contacts' => $this->record->contract->client->contact_information]
                            );
                        }),
                        Placeholder::make('from')->content($this->record->contract->client->created_at),
                    ])
                    ->compact()
                    ->collapsed(false)
                    ->inlineLabel()
                    ->columnSpan(1),
            ])
                ->columns(4)
                ->columnSpanFull()
        ];
    }

    private function getJobOrders($jobOrders): array
    {
        $schema = [];
        foreach ($jobOrders->all() as $jo) {
            array_push(
                $schema,
                Section::make($jo->target_date . '  ' . $jo->status)->schema([
                    Grid::make([8])->schema([
                        Placeholder::make('Code')->content($jo->code),
                        Placeholder::make('Status')->content($jo->status),
                        Placeholder::make('Type')->content($jo->job_order_type),
                        Placeholder::make('Summary')->content($jo->summary),
                    ])->columns(5)->extraAttributes(['style' => 'color:#fafafa'])
                ])->compact()->collapsed()->collapsible()->extraAttributes(
                    function () use ($jo) {
                        $colors  = [
                            "completed" => "#86efac",
                            "cancelled" => '#6b7280',
                            "unscheduled" => "#f43f5e",
                            "scheduled" => "#c2410c",
                        ];
                        return ['style' => 'color:' . $colors[$jo->status]];
                    }
                )
            );
        }

        return $schema;
    }

    public function getColumnSpan(): int | string | array
    {
        return "full";
    }
}
