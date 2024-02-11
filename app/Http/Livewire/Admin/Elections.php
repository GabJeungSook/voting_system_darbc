<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class Elections extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return Election::query();
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
            ->label('Add New Election')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->action(function (array $data): void {
                DB::beginTransaction();
                Election::create([
                    'name' => $data['name'],
                    'date_from' => $data['date_from'],
                    'date_to' => $data['date_to'],
                    'is_active' => 0,
                ]);
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Data was successfully saved'
                );
            })
            ->form([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\DatePicker::make('date_from')->required(),
                    Forms\Components\DatePicker::make('date_to')->required(),
                ])->columns(2),
            ])
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->button()
            ->color('success')
            ->mountUsing(fn (Forms\ComponentContainer $form, Election $record) => $form->fill([
                'name' => $record->name,
                'date_from' => $record->date_from,
                'date_to' => $record->date_to,
            ]))
            ->form([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\DatePicker::make('date_from')->required(),
                    Forms\Components\DatePicker::make('date_to')->required(),
                ])->columns(2),
            ])
            ->action(function (Election $record, array $data): void {
                DB::beginTransaction();
                $record->name = $data['name'];
                $record->date_from = $data['date_from'];
                $record->date_to = $data['date_to'];
                $record->save();
                DB::commit();
            }),
            Action::make('delete')
            ->icon('heroicon-o-trash')
            ->button()
            ->color('danger')
            ->action(function (Election $record): void {
                $record->delete();
            })
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->is_active == false),

        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
            ->label('NAME')
            ->formatStateUsing(fn (Election $record) => strtoupper($record->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('date_from')
            ->label('FROM')
            ->date('F d, Y')
            ->searchable(),
            Tables\Columns\TextColumn::make('date_to')
            ->label('TO')
            ->date('F d, Y')
            ->searchable(),
            Tables\Columns\ToggleColumn::make('is_active')
            ->label('ACTIVE')
            ->onColor('success')
            ->onIcon('heroicon-s-check')
            ->offIcon('heroicon-s-x')
            ->offColor('danger')
            ->updateStateUsing(function ($record, $state) {
                $activeElection = Election::where('is_active', true)->exists();
                if($record->is_active)
                {
                    $record->update(['is_active' => false]);
                }else{
                    if($activeElection)
                    {
                        Notification::make()
                        ->title('Operation Failed')
                        ->body('You can only activate one (1) election at a time.')
                        ->danger()
                        ->send();
                        $this->redirect(route('admin.elections'));
                    } else {
                        $record->is_active == false ? $record->update(['is_active' => true]) : $record->update(['is_active' => false]);
                    }
                }

            })
        ];
    }

    public function render()
    {
        return view('livewire.admin.elections');
    }
}
