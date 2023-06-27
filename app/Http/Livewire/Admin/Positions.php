<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class Positions extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        // return Position::query();
        return Position::query()->whereHas('election', function($query) {
            $query->where('is_active', 1);
        });
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
            ->label('Add New Position')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->action(function (array $data): void {
                DB::beginTransaction();
                Position::create([
                    'election_id' => $data['election_id'],
                    'name' => $data['name'],
                    'number_of_candidates' => $data['number_of_candidates'],
                    'number_of_winners' => $data['number_of_winners'],
                ]);
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Data was successfully saved'
                );
            })
            ->form([
                Forms\Components\Select::make('election_id')
                ->label('Election')
                ->options(Election::where('is_active', 1)->pluck('name', 'id'))
                ->default(1)
                ->disablePlaceholderSelection()->required(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('number_of_candidates')
                ->numeric()
                ->required(),
                Forms\Components\TextInput::make('number_of_winners')
                ->numeric()
                ->required(),
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
            ->mountUsing(fn (Forms\ComponentContainer $form, Position $record) => $form->fill([
                'name' => $record->name,
                'number_of_candidates' => $record->number_of_candidates,
                'number_of_winners' => $record->number_of_winners,
            ]))
            ->form([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('number_of_candidates')
                ->numeric()
                ->required(),
                Forms\Components\TextInput::make('number_of_winners')
                ->numeric()
                ->required(),
            ])
            ->action(function (Position $record, array $data): void {
                DB::beginTransaction();
                $record->name = $data['name'];
                $record->number_of_candidates = $data['number_of_candidates'];
                $record->number_of_winners = $data['number_of_winners'];
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
            Tables\Columns\TextColumn::make('election.name')
            ->label('ELECTION NAME')
            ->formatStateUsing(fn (Position $record) => strtoupper($record->election->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('name')
            ->label('NAME')
            ->searchable(),
            Tables\Columns\TextColumn::make('number_of_candidates')
            ->label('NUMBER OF CANDIDATES')
            ->searchable(),
            Tables\Columns\TextColumn::make('number_of_winners')
            ->label('NUMBER OF WINNERS')
            ->searchable(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.positions');
    }
}
