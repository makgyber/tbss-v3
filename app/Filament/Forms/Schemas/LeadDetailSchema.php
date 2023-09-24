<?php

namespace App\Filament\Forms\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class LeadDetailSchema
{
    public static function getSchema(): array
    {
        return [
            Textarea::make('concerns')
                ->label('Summary of conversation with client')
                ->required()
                ->rows(3),
            SpatieTagsInput::make('visible_signs_seen_by_client')
                ->type('visible_signs'),
        ];
    }
}
