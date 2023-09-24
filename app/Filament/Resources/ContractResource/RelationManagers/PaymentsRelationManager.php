<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('details')
                    ->hint('Ex. Downpayment, Advance, Consultation Fee')
                    ->required()
                    ->maxLength(255),
                DatePicker::make("due_at")
                    ->required(),
                Select::make('status')
                    ->options(config('tbss.payment_status')),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('due_at')->date("M j, Y"),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('details'),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'free',
                        'warning' => 'with balance',
                        'success' => 'paid',
                        'danger' => 'pending',
                    ]),
                TextColumn::make('verifiedBy.name'),
                TextColumn::make('updated_at')->date("M j, Y")->label('Last update'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['verified_by'] = auth()->user()->id;
                        $data['status'] = 'pending';
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
