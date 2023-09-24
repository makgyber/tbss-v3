<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class MultiImages extends Column
{
    protected string $view = 'tables.columns.multi-images';

    public function getExtraAttributes(): array
    {
        return [];
    }

    public function getExtraImgAttributeBag(): array
    {
        return [];
    }
}
