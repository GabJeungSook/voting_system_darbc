<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use App\Models\Election;
use App\Models\RegisteredMember;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;


class Voted extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    public $election_id;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('election_id', $this->election_id)->whereHas('votes');
    }

    protected function getTablePollingInterval(): ?string
    {
        return '3s';
    }

    public function getTableActions()
    {
        return [
            Action::make('view_ballot')
            ->icon('heroicon-o-eye')
            ->button()
            ->color('warning')
            ->url(fn (RegisteredMember $record): string => route('voting.view-ballot', $record)),
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

    public function mount(): void
    {
        $this->election_id = Election::where('is_active', true)->first()?->id;
    }

    public function returnToVotingIndex()
    {
        return redirect()->route('voting.voting-module');
    }


    public function render()
    {
        return view('livewire.voting.voted');
    }
}
