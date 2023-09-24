<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use App\Filament\Resources\FindingResource;
use App\Models\PestType;
use App\Tables\Columns\MultiImages;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class FindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    protected static ?string $recordTitleAttribute = 'infestation';


    public static function getTitleForRecord(Model $ownerRecord): string
    {

        return static::getTitle() . ' (' . $ownerRecord->{static::getRelationshipName()}->count() . ')';
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
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
                            ->required()
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
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('degree')
                            ->options(config('tbss.infestation_degree'))
                            ->required(),
                    ])->columns(3),

                Textarea::make('remarks')
                    // ->disableToolbarButtons(['attachFiles'])
                    ->required()
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('attachedfindings')
                    ->multiple()
                    ->enableDownload()
                    ->enableOpen()
                    ->preserveFilenames()
                    ->responsiveImages()->enableReordering()
                    ->columnSpanFull(),
                Textarea::make('review')
                    ->label('Supervisor Review')
                    // ->disableToolbarButtons(['attachFiles'])
                    // ->required()
                    ->columnSpanFull(),
                Hidden::make('noted_by')
                    ->default(Auth()->user()->id),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->columns([
                Stack::make(
                    [
                        Tables\Columns\TextColumn::make('location')->size('lg')->weight('bold')
                            ->extraAttributes(['class' => 'py-4']),
                        MultiImages::make('media'),
                        Tables\Columns\TextColumn::make('remarks')->size('md')->html()
                            ->extraAttributes(['class' => 'py-6']),
                        Split::make([
                            Tables\Columns\TextColumn::make('infestation')
                                ->prefix("Infestation: ")
                                ->color('success')
                                ->weight('bold'),
                            Tables\Columns\BadgeColumn::make('degree')
                                ->colors([
                                    'warning',
                                    'secondary' => 'none',
                                    'primary' => 'light',
                                    'success' => 'medium',
                                    'danger' => 'heavy',
                                ])
                                ->alignRight(true)
                                ->size('sm'),

                        ]),
                        Tables\Columns\TextColumn::make('review')->size('md')->html()
                            ->extraAttributes(['class' => 'py-4 font-semibold text-red-500']),

                    ]
                )
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
                Tables\Actions\Action::make('view-images')
                    ->label('View Images')
                    ->action(fn ($record) => $record)
                    ->modalContent(fn ($record) => view('tables.columns.multi-images', ['record' => $record])),
                Tables\Actions\Action::make('Logs')
                    ->url(fn ($record) => FindingResource::getUrl('edit', ['record' => $record->id, 'activeRelationManager' => 0]))
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort("updated_at", "desc");
    }
}
