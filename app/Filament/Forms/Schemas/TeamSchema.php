<?php

namespace App\Filament\Forms\Schemas;

use App\Models\JobOrder;
use App\Models\Leave;
use App\Models\Schedule;
use App\Models\Team;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TeamSchema
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('code')
                ->required(),
            CheckboxList::make('technicians')
                ->label('Technicians')

                ->relationship('users', 'name', function (RelationManager $livewire) {
                    $assigned = User::select(DB::raw("concat_ws(' ', users.name, ' <<** TEAM ', teams.code, ' **>>') as name"), "users.id")
                        ->whereHas("roles", function ($q) {
                            $q->where("name", config('tbss.operations.technician.role'));
                        })
                        ->whereNotIn("users.id", Leave::select('user_id')->where('leave_date', $livewire->ownerRecord->visit_at))
                        ->orderBy('name')
                        ->join('team_user', 'team_user.user_id', '=', 'users.id')
                        ->join('teams', 'teams.id', '=', 'team_user.team_id')
                        ->where('teams.schedule_id', '=', $livewire->ownerRecord->id);

                    $unassigned = User::select("users.name", "users.id")
                        ->whereHas("roles", function ($q) {
                            $q->where("name", config('tbss.operations.technician.role'));
                        })
                        ->whereNotIn("users.id", Leave::select('user_id')->where('leave_date', $livewire->ownerRecord->visit_at))
                        ->orderBy('name');

                    return $unassigned->union($assigned);
                })

                ->columns(2),
            CheckboxList::make('job_orders')
                ->relationship('jobOrders', 'code', function (RelationManager $livewire) {

                    $unassigned = JobOrder::select(DB::raw("concat_ws(' : ', clients.name, job_orders.code, job_orders.job_order_type, addresses.street) as code "), 'job_orders.id')
                        ->whereDate('target_date', $livewire->ownerRecord->visit_at)
                        ->whereNotIn('status', ['cancelled', 'postponed'])
                        ->whereDoesntHave('teams')
                        ->join('addresses', 'job_orders.address_id', '=', 'addresses.id')
                        ->join('clients', 'clients.id', '=', 'addresses.client_id');



                    if (isset($livewire->mountedTableActionData['id'])) {
                        $assignedToTeam = JobOrder::select(DB::raw("concat_ws(' : ', clients.name, job_orders.code, job_orders.job_order_type, addresses.street) as code "), 'job_orders.id')
                            ->whereDate('target_date', $livewire->ownerRecord->visit_at)
                            ->whereNotIn('status', ['cancelled', 'postponed'])
                            ->whereHas('teams', function ($q) use ($livewire) {
                                $q->where('team_id', $livewire->mountedTableActionData['id']);
                            })
                            ->join('addresses', 'job_orders.address_id', '=', 'addresses.id')
                            ->join('clients', 'clients.id', '=', 'addresses.client_id');


                        return $unassigned->union($assignedToTeam);
                    }

                    return $unassigned;
                })
                ->saveRelationshipsUsing(function (RelationManager $livewire) {

                    if ($livewire->mountedTableAction ===  'edit') {

                        $team = Team::find($livewire->mountedTableActionData['id']);

                        $existingJobOrders = $team->jobOrders->pluck('id')->toArray();
                        $newJobOrders = $livewire->mountedTableActionData['job_orders'];

                        $difference = array_diff($existingJobOrders, $newJobOrders);

                        $team->jobOrders()->sync($newJobOrders);

                        foreach ($difference as $jobOrderId) {
                            $jobOrder = JobOrder::find($jobOrderId);
                            $jobOrder->status = 'unscheduled';
                            $jobOrder->save();
                        }

                        foreach ($newJobOrders as $jobOrderId) {
                            $jobOrder = JobOrder::find($jobOrderId);
                            $jobOrder->status = 'scheduled';
                            $jobOrder->save();
                        }
                    } else {

                        $team = Team::where('code', $livewire->mountedTableActionData['code'])
                            ->where('schedule_id', $livewire->ownerRecord->id)->first();

                        $newJobOrders = $livewire->mountedTableActionData['job_orders'];

                        $team->jobOrders()->sync($newJobOrders);

                        foreach ($newJobOrders as $jobOrderId) {
                            $jobOrder = JobOrder::find($jobOrderId);
                            $jobOrder->status = 'scheduled';
                            $jobOrder->save();
                        }
                    }
                })
                ->columns(1),


        ];
    }
}
