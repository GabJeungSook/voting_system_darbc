<?php

namespace App\Http\Livewire\Voting;

use Livewire\Component;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Election;
use App\Models\Vote;
use App\Models\RegisteredMember;

class Dashboard extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    public $election_id;
    public $voter_count;
    public $voter_count_total;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('election_id', $this->election_id)->whereHas('votes', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    protected function getTableColumns(): array
    {
        return [
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
            Tables\Columns\TextColumn::make('votes_count')
            ->getStateUsing(function (RegisteredMember $record) {
                return $record->votes()->first()->created_at;
            })
            ->date('F d, Y h:i:s A')
            ->label('DATE VOTED'),
        ];
    }

    public function mount(): void
    {
        $this->election_id = Election::where('is_active', true)->first()?->id;
        $this->voter_count = RegisteredMember::where('election_id', $this->election_id)->whereHas('votes', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $this->voter_count_total = Vote::where('election_id', $this->election_id)->distinct('registered_member_id')->count();
    }

    public function render()
    {
        return view('livewire.voting.dashboard');
    }
}
