<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\CreateEntomSchema;
use App\Filament\Forms\Schemas\LeadDetailSchema;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Filament\Resources\LeadResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\LeadResource\RelationManagers\EntomsRelationManager;
use App\Filament\Resources\LeadResource\RelationManagers\SitesRelationManager;
use App\Filament\Resources\LeadResource\Widgets\AddressList;
use App\Filament\Resources\LeadResource\Widgets\ClientWidget;
use App\Filament\Resources\LeadResource\Widgets\JobOrderDetails;
use App\Filament\Resources\LeadResource\Widgets\LeadDetailWidget;
use App\Models\Entom;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $model = Lead::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right';

    protected static ?string $navigationBadge = '1';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')->searchable()->sortable()->linkRecord(),
                Tables\Columns\ViewColumn::make('client')
                    ->label('Address and Contact Info')
                    ->view('filament.tables.columns.client-contact-address'),

                Tables\Columns\TextColumn::make('concerns')->wrap()->size('sm'),
                Tables\Columns\TextColumn::make('status')->color('warning')->searchable()->sortable()
                    ->description(fn (Lead $record) => $record->updated_at),
                TextColumn::make('assignedTo.name'),
                TextColumn::make('received_on')->label('Date received')->dateTime('M j, Y'),
                TextColumn::make('source'),

            ])
            ->filters([

                SelectFilter::make('status')
                    ->options(config('tbss.lead_status')),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

            ])
            ->actions([
                ActionGroup::make([

                    Action::make('create_entom')
                        ->action(function (Lead $record, array $data) {
                            $record->status = 'pending entom';
                            $record->save();
                            $record->refresh();
                            $data['lead_id'] = $record->id;
                            $entom = Entom::create($data);

                            $recipient = User::find($data['assigned_to']);
                            Notification::make()
                                ->title('ENTOM request initiated')
                                ->body('Please check new ENTOM')
                                ->sendToDatabase($recipient);
                        })
                        ->form(fn ($record) => CreateEntomSchema::getSchema($record))
                        ->visible(fn (Lead $record) => $record->status == 'requested entom'),


                    // Tables\Actions\EditAction::make(),
                    // Tables\Actions\ViewAction::make(),
                    Action::make('update_status')
                        ->action(function (Lead $record, array $data) {
                            $record->status = $data['status'];
                            $record->save();
                        })
                        ->form([
                            Select::make('status')
                                ->options(config('tbss.lead_status'))
                        ]),
                    Action::make('assign_to')
                        ->action(function (Lead $record, array $data) {
                            $record->assigned_to = $data['assign_to'];
                            $record->save();
                        })
                        ->form([
                            Select::make('assign_to')
                                ->options(User::all()->pluck('name', 'id'))
                        ]),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // FilamentExportBulkAction::make('export'),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export')
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // SitesRelationManager::class
            CommentsRelationManager::class,
            EntomsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {

        return [
            ClientWidget::class,
            LeadDetailWidget::class,
            AddressList::class,
            JobOrderDetails::class
        ];
    }
}
