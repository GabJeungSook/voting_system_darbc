<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class VotingModule extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query();
    }

    public function getTableActions()
    {
        return [
            Action::make('vote')
            ->icon('heroicon-o-user')
            ->button()
            ->color('success')
            ->url(fn (RegisteredMember $record): string => route('voting.cast-vote', $record))
            ->visible(fn (RegisteredMember $record) => $record->votes->isEmpty()),
            Action::make('view_ballot')
            ->icon('heroicon-o-eye')
            ->button()
            ->color('warning')
            ->url(fn (RegisteredMember $record): string => route('voting.cast-vote', $record))
            ->visible(fn (RegisteredMember $record) => !$record->votes->isEmpty()),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('election.name')
            ->label('ELECTION NAME')
            ->formatStateUsing(fn (RegisteredMember $record) => strtoupper($record->election->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('darbc_id')
            ->label('DARBC ID')
            ->searchable(),
            Tables\Columns\TextColumn::make('first_name')
            ->label('FIRST NAME')
            ->formatStateUsing(fn (RegisteredMember $record) => strtoupper($record->first_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
            ->label('MIDDLE NAME')
            ->formatStateUsing(fn (RegisteredMember $record) => strtoupper($record->middle_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
            ->label('LAST NAME')
            ->formatStateUsing(fn (RegisteredMember $record) => strtoupper($record->last_name))
            ->searchable(),
        ];
    }

    public function render()
    {
        return view('livewire.voting.voting-module');
    }
}
