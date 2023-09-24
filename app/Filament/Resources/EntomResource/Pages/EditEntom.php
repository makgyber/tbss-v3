<?php

namespace App\Filament\Resources\EntomResource\Pages;

use App\Filament\Forms\Schemas\CreateEntomSchema;
use App\Filament\Resources\EntomResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntom extends EditRecord
{
    protected static string $resource = EntomResource::class;

    protected $listeners = ['refresh' => '$refresh'];

    protected function getFormSchema(): array
    {

        return [
            Fieldset::make('Entom Details')
                ->schema([
                    ...CreateEntomSchema::getSchema(null),
                ])
                ->columns(2)
        ];
    }
}
