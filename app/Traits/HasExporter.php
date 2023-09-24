<?php

namespace App\Traits;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

trait HasExporter
{

    protected function getTableBulkActions(): array
    {
        return [

            // FilamentExportBulkAction::make('Export'),

        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [

            // FilamentExportHeaderAction::make('Export'),

        ];
    }
}
