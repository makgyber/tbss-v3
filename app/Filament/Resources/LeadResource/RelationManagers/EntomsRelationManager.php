<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use App\Filament\Forms\Schemas\CreateEntomSchema;
use App\Filament\Resources\EntomResource;
use App\Filament\Resources\EntomResource\Pages\CreateEntom;
use App\Models\Entom;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntomsRelationManager extends RelationManager
{
    protected static string $relationship = 'entom';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {

        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name'),
                Tables\Columns\TextColumn::make('site.label'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->hidden(fn ($livewire) => $livewire->ownerRecord->status != 'pending entom')
                    ->url(fn ($livewire) => EntomResource::getUrl('create', ['ownerRecord' => $livewire->ownerRecord->getKey()])),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Entom $record): string => route('filament.resources.entoms.view', $record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
