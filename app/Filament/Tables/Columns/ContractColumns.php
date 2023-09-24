<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class ContractColumns
{
    public static function getColumns(): array
    {
        return [
            BadgeColumn::make('status')->enum(config('tbss.contract_status'))
                ->colors([
                    'secondary' => 'pending',
                    'warning' => 'submitted',
                    'success' => 'closed',
                    'danger' => 'declined',
                ])->sortable()->toggleable(),
            TextColumn::make("code")->toggleable()->searchable()->sortable()->wrap(),
            TextColumn::make("client.name")->toggleable()->searchable()->sortable()->wrap(),
            TextColumn::make("contract_type")->sortable()->toggleable(),
            TextColumn::make("contract_price")
                ->money('php', shouldConvert: true)->alignRight()
                ->sortable()->searchable()->toggleable(),
            TextColumn::make("addresses.street")->searchable()->sortable()->wrap()->toggleable(),
            TextColumn::make("visits")->sortable()->toggleable(),
            TextColumn::make("start")->date("M j, Y")->sortable()->toggleable(),
            TextColumn::make("end")->date("M j, Y")->sortable()->toggleable(),
            TextColumn::make("assignedTo.name")->label("Assigned To")->toggleable()->searchable(),
            TextColumn::make("media.file_name")->label("Document")->wrap()->toggleable(),
        ];
    }
}
