<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Election;
use App\Models\RegisteredMember;

class Dashboard extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    public $election_id;
    public $registered_count;
    public $registered_count_total;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('user_id', auth()->user()->id);
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
            Tables\Columns\TextColumn::make('created_at')
            ->label('REGISTRATION DATE')
            ->date('F d, Y h:i:s A')
            ->searchable(),
        ];
    }

    public function mount(): void
    {
        $this->election_id = Election::where('is_active', true)->first()?->id;
        $this->registered_count = RegisteredMember::where('election_id', $this->election_id)->where('user_id', auth()->user()->id)->count();
        $this->registered_count_total = RegisteredMember::count();
    }

    public function render()
    {
        return view('livewire.registration.dashboard');
    }
}