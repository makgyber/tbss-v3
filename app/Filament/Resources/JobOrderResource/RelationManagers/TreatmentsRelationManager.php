<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use App\Models\TreatmentType;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    protected static ?string $recordTitleAttribute = 'treatment_type';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('treatment_type')
                            ->datalist(function () {
                                $types = TreatmentType::all();
                                $list = [];
                                foreach ($types as $type) {
                                    $list[$type->name] = $type->name;
                                }
                                return $list;
                            })
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->datalist([
                                'bedroom',
                                'kitchen',
                                'garden',
                                'master bedroom',
                                'toilet',
                                'bathroom',
                                '1st floor',
                                '2nd floor',
                            ])
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('quantity'),
                    ])->columns(3),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('attachedtreatments')
                    ->multiple()
                    ->enableDownload()
                    ->enableOpen()
                    ->preserveFilenames()
                    ->responsiveImages()->enableReordering()
                    ->columnSpanFull(),
                Hidden::make('user_id')
                    ->default(Auth()->user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('treatment_type'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
