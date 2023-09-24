<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class JobOrderColumns
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('target_date')->date('Y-m-d h:i a')->sortable(),
            TextColumn::make('address.client.name')->searchable()->wrap(),
            TextColumn::make('job_order_type')->searchable()->sortable(),
            TextColumn::make('code')->searchable(),
            TextColumn::make('status')->sortable(),
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
                ->wrap()->size('sm'),
            TextColumn::make('summary')->wrap()->size('sm'),
            TextColumn::make('createdBy.name'),
        ];
    }
}
