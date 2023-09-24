<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Schemas\CreateEntomSchema;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\EntomResource\Pages;
use App\Filament\Resources\EntomResource\RelationManagers;
use App\Filament\Resources\EntomResource\RelationManagers\FindingsRelationManager;
use App\Filament\Resources\EntomResource\RelationManagers\JobOrdersRelationManager;
use App\Filament\Resources\ContractResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\EntomResource\RelationManagers\UsersRelationManager;
use App\Models\City;
use App\Models\Entom;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Modal\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Livewire;

class EntomResource extends Resource
{

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $model = Entom::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?int $navigationSort = 2;
    protected $listeners = ['refresh' => '$refresh'];

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
                Tables\Columns\BadgeColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('client.name')->sortable()->searchable()->linkRecord(),

                Tables\Columns\ViewColumn::make('site')
                    ->label('Site Details')
                    ->view('filament.tables.columns.client-site'),
                TextColumn::make('client_requests')->wrap()->size('sm'),

                Tables\Columns\TextColumn::make('target_date')->size('sm')
                    ->date('M j, Y g:i a')->sortable(),

            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('target_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['target_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '=', $date),
                            );
                    }),
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'pending entom' => 'pending entom',
                                'requested entom' => 'requested entom',
                                'completed entom' => 'completed entom',
                                'declined entom' => 'declined entom',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['status'],
                                fn (Builder $query, $status): Builder => $query->whereDate('status', '=', $status),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('View Lead')
                        ->url(function ($record) {
                            if ($record)
                                return LeadResource::getUrl('view', [$record->lead_id]);
                        })
                        ->hidden(!isset($record)),
                    Tables\Actions\Action::make('update_status')
                        ->action(function (Entom $record, array $data) {
                            $record->status = $data['status'];
                            $record->save();
                        })
                        ->form([
                            Select::make('status')
                                ->options(config('tbss.entom_status'))
                        ]),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // Tables\Actions\BulkAction::make('assign_technicians')
                //     ->form([
                //         Repeater::make('technicians')
                //             ->schema([

                //                 Select::make('user_id')
                //                     ->label('Technician')
                //                     ->options(fn () => User::query()->pluck('name', 'id'))
                //                     ->required(),
                //             ])
                //     ])
                // ->action(function (Collection $records, array $data) {
                //     foreach ($records as $record) {
                //         foreach ($data as $users) {
                //             foreach ($users as $user) {
                //                 if (!$record->users->contains($user['user_id'])) {
                //                     $record->users()->attach($user['user_id']);
                //                     $record->save();
                //                 }
                //             }
                //         }
                //     }
                // })
                // ->deselectRecordsAfterCompletion(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            JobOrdersRelationManager::class,
            PaymentsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntoms::route('/'),
            'create' => Pages\CreateEntom::route('/create'),
            'edit' => Pages\EditEntom::route('/{record}/edit'),
            'view' => Pages\ViewEntom::route('/{record}'),
        ];
    }
}
