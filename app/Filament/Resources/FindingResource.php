<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\FindingResource\Pages;
use App\Filament\Resources\FindingResource\RelationManagers;
use App\Models\Finding;
use App\Models\PestType;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FindingResource extends Resource
{
    protected static ?string $model = Finding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Images')->schema([
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->collection('attachedfindings')
                        ->multiple()
                        ->enableDownload()
                        ->enableOpen()
                        ->preserveFilenames()
                        ->responsiveImages()->enableReordering()
                        ->columnSpanFull(),
                    Textarea::make('Caption')
                        ->required()
                        ->columnSpanFull(),
                ]),


                Section::make("Audited Details")
                    ->schema([
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
                            ->required(false)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('infestation')
                            ->datalist(function () {
                                $types = PestType::all();
                                $list = [];
                                foreach ($types as $type) {
                                    $list[$type->name] = $type->name;
                                }
                                return $list;
                            })
                            ->required(false)
                            ->maxLength(255),
                        Forms\Components\Select::make('degree')
                            ->options(config('tbss.infestation_degree'))
                            ->required(false),
                    ])->columns(3)->collapsed(),


                Section::make('Supervisor')->schema([
                    Textarea::make('review')
                        ->label("Supervisor Review")
                        // ->disableToolbarButtons(['attachFiles'])
                        ->columnSpanFull(),
                ])->collapsed(),

                Hidden::make('noted_by')
                    ->default(Auth()->user()->id),
                Hidden::make('job_order_id')
                    ->default(request('job_order_id')),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->date('Y-m-d h:s a')->wrap(),
                TextColumn::make('jobOrder.code')
                    ->wrap()
                    ->url(fn ($record) => JobOrderResource::getUrl('edit', ['record' => $record->jobOrder->id, 'activeRelationManager' => 0])),
                TextColumn::make('jobOrder.client.name')
                    ->wrap()
                    ->url(fn ($record) => ClientResource::getUrl('edit', ['record' => $record->jobOrder->client->id])),
                TextColumn::make('location')->wrap(),
                TextColumn::make('infestation')->wrap(),
                TextColumn::make('degree'),
                TextColumn::make('remarks')->wrap()->html(),
                TextColumn::make('review')->label('Supervisor review')->wrap()->html(),
                TextColumn::make('noted_by.name'),
                SpatieMediaLibraryImageColumn::make("media"),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFindings::route('/'),
            'create' => Pages\CreateFinding::route('/create'),
            'edit' => Pages\EditFinding::route('/{record}/edit'),
        ];
    }
}
