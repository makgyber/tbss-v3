<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\View;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    protected function getFormSchema(): array
    {
        return [
            View::make('media')->view('filament.tables.columns.video'),
        ];
    }

    public function getHeading(): string | Htmlable
    {
        return $this->record->title;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return $this->record->summary;
    }
}
