<?php

namespace App\Http\Livewire\Admin;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use Livewire\Component;
use App\Models\Election;
use Mike42\Escpos\Printer;
use App\Models\VoidedMember;
use App\Models\RegisteredMember;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Illuminate\Database\Eloquent\Builder;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class VoidMemberVote extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    public $election_id;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('election_id', $this->election_id)->whereHas('votes');
    }

    public function getTableActions()
    {
        return [
            Action::make('void_vote')
            ->label('Void Vote')
            ->icon('heroicon-o-trash')
            ->button()
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->has_voted === 1)
            ->mountUsing(fn (Forms\ComponentContainer $form, RegisteredMember $record) => $form->fill([
                'registered_member' => strtoupper($record->first_name.' '.$record->middle_name.' '.$record->last_name),
            ]))
            ->form([
                Forms\Components\TextInput::make('registered_member')->required()->disabled(),
                // Forms\Components\TextInput::make('user_id')->label('Voted At')->required()->disabled(),
                Forms\Components\Select::make('member_id')
                ->label('Who voted for the member?')
                ->preload()
                ->options(Member::pluck('full_name', 'id'))
                ->searchable()
                ->reactive()
                ->visible(fn ($get) => $get('unidentified') != true),
                Forms\Components\Toggle::make('unidentified')
                ->default(true)
                ->reactive(),
                Forms\Components\Textarea::make('note')
            ])
            ->action(function (RegisteredMember $record, array $data) {
                DB::beginTransaction();
                VoidedMember::create([
                    'registered_member_id' => $record->member->id,
                    'member_id' => $data['unidentified'] ? null : $data['member_id'],
                    'user_id' => $record->votes()->first()->user_id,
                    'note' =>  $data['note'],
                    'type' =>  'VOTING',
                ]);

                $record->votes()->delete();
                $record->has_voted = 0;
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Registered Member Votes are Voided'
                );
            })
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('darbc_id')
            ->label('DARBC ID')
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('user.name')
            ->label('REGISTERED AT')
            // ->formatStateUsing(fn (RegisteredMember $record) => strtoupper($record->first_name))
            ->searchable()->sortable(),
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

    public function mount()
    {
        $this->election_id = Election::where('is_active', true)->first()?->id;
    }


    public function render()
    {
        return view('livewire.admin.void-member-vote');
    }
}
