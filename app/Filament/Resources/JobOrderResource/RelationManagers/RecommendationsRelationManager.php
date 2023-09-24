<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use App\Models\ServiceType;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecommendationsRelationManager extends RelationManager
{
    protected static string $relationship = 'recommendations';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getTitleForRecord(Model $ownerRecord): string
    {

        return static::getTitle() . ' (' . $ownerRecord->{static::getRelationshipName()}->count() . ')';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('service_type')
                    ->datalist(function () {
                        $types = ServiceType::all();
                        $list = [];
                        foreach ($types as $type) {
                            $list[$type->name] = $type->name;
                        }
                        return $list;
                    })
                    ->required(),

                Select::make('priority')
                    ->options(config('tbss.priority')),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->columnSpan(2),
                SpatieMediaLibraryFileUpload::make('recommendations')->label('Attachments')
                    ->collection('attachedrecommendations')
                    ->multiple()
                    ->enableDownload()
                    ->enableOpen()
                    ->preserveFilenames()
                    ->responsiveImages()->enableReordering()
                    ->columnSpanFull(),
                Hidden::make('recommended_by')
                    ->default(Auth()->user()->id),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_type'),
                TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('description'),
                SpatieMediaLibraryImageColumn::make('recommendations')->collection('attachedrecommendations')->label('Attachments'),
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
            ])->defaultSort("updated_at", "desc");
    }
}
