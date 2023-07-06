<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\Printer;
use App\Models\User;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class Printers extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return Printer::query();
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
            ->label('Add New Printer')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->action(function (array $data): void {
                DB::beginTransaction();
                Printer::create([
                    'user_id' => $data['user_id'],
                    'ip_address' => $data['ip_address'],
                ]);
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Data was successfully saved'
                );
            })
            ->form([
                Forms\Components\Select::make('user_id')
                ->label('Election')
                ->options(User::where('role_id', '!=', 1)->whereDoesntHave('printer')->pluck('name', 'id'))->required(),
                Forms\Components\TextInput::make('ip_address')->label('IP Address')->required(),
            ])
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('edit')
            ->label('Edit IP Address')
            ->icon('heroicon-o-pencil')
            ->button()
            ->color('success')
            ->mountUsing(fn (Forms\ComponentContainer $form, Printer $record) => $form->fill([
                'ip_address' => $record->ip_address,
            ]))
            ->form([
                Forms\Components\TextInput::make('ip_address')->label('IP Address')->required(),
            ])
            ->action(function (Printer $record, array $data): void {
                DB::beginTransaction();
                $record->ip_address = $data['ip_address'];
                $record->save();
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Data was successfully updated'
                );
            })
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
            ->label('USER')
            ->formatStateUsing(fn (Printer $record) => strtoupper($record->user->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('user.role_id')
            ->label('ASSIGNMENT')
            ->formatStateUsing(fn (Printer $record) => strtoupper($record->user->roles->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('ip_address')
            ->label('IP ADDRESS')
            ->searchable(),
        ];
    }


    public function render()
    {
        return view('livewire.admin.printers');
    }
}
