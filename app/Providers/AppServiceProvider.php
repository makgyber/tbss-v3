<?php

namespace App\Providers;

use Filament\Tables\Columns\Column;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Column::macro('linkRecord', function ($view = 'view') {
            return $this->url(function ($record) use ($view) {
                if ($record === null) {
                    return null;
                }

                $selectedResource = null;
                $relationship = Str::before($this->getName(), '.');
                $relatedRecord = $record->{$relationship};

                if ($relatedRecord === null) {
                    return null;
                }

                foreach (Filament::getResources() as $resource) {
                    if ($relatedRecord instanceof ($resource::getModel())) {
                        $selectedResource = $resource;

                        break;
                    }
                }

                return $selectedResource::getUrl($view, ['record', $relatedRecord->getKey()]);
            });
        });
    }
}
