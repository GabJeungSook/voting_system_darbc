<?php

namespace App\Http\Livewire\Admin;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use WireUi\Traits\Actions;
use App\Models\RegisteredMember;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class Candidates extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return Candidate::query()->whereHas('election', function($query) {
            $query->where('is_active', 1);
        });
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
            ->label('Add New Candidate')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->action(function (array $data): void {
                $position_count = Candidate::where('position_id', $data['position_id'])->count();
                $position_max_candidate = Position::where('id', $data['position_id'])->first()->number_of_candidates;
                if($position_count === $position_max_candidate)
                {
                    Notification::make()
                    ->title('Operation Failed')
                    ->body('The selected position has a maximum of '.$position_max_candidate.' candidates only.')
                    ->danger()
                    ->send();
                }else{
                    DB::beginTransaction();
                    Candidate::create([
                       'election_id' => $data['election_id'],
                       'position_id' => $data['position_id'],
                       'first_name' => $data['first_name'],
                       'middle_name' => $data['middle_name'],
                       'last_name' => $data['last_name'],
                       'image_path' => $data['image_path'],
                    ]);
                    DB::commit();
                   $this->dialog()->success(
                       $title = 'Success',
                       $description = 'Data was successfully saved'
                   );
                }
            })
            ->form([
                Forms\Components\Select::make('election_id')
                ->label('Election')
                ->options(Election::where('is_active', 1)->pluck('name', 'id'))->required(),
                Forms\Components\Select::make('position_id')
                ->label('Position')
                ->options(Position::whereHas('election', function($query){$query->where('is_active', 1);})->pluck('name', 'id'))
                ->required(),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('first_name')->required(),
                    Forms\Components\TextInput::make('middle_name'),
                    Forms\Components\TextInput::make('last_name')->required(),
                ])->columns(3),
                Forms\Components\FileUpload::make('image_path')
                ->directory('candidate-photos')->preserveFilenames()->image()
                ->label('Photo')->required(),
            ])->visible(fn () => RegisteredMember::count() < 0)
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->button()
            ->color('success')
            ->mountUsing(fn (Forms\ComponentContainer $form, Candidate $record) => $form->fill([
                'position_id' => $record->position_id,
                'first_name' => $record->first_name,
                'middle_name' => $record->middle_name,
                'last_name' => $record->last_name,
            ]))
            ->form([
                Forms\Components\Select::make('position_id')
                ->label('Position')
                ->options(Position::whereHas('election', function($query){$query->where('is_active', 1);})->pluck('name', 'id'))
                ->required(),
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('first_name')->required(),
                    Forms\Components\TextInput::make('middle_name'),
                    Forms\Components\TextInput::make('last_name')->required(),
                ])->columns(3),
                Forms\Components\FileUpload::make('image_path')
                ->directory('candidate-photos')->preserveFilenames()->image()
                ->label('Photo'),
            ])
            ->action(function (Candidate $record, array $data): void {
                DB::beginTransaction();
                $record->position_id = $data['position_id'];
                $record->first_name = $data['first_name'];
                $record->middle_name = $data['middle_name'];
                $record->last_name = $data['last_name'];
                if($data['image_path'] != null)
                {
                    $record->image_path = $data['image_path'];
                }
                $record->save();
                DB::commit();
                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Data was successfully updated'
                );
            })->visible(fn () => RegisteredMember::count() < 0),
            Action::make('delete')
            ->icon('heroicon-o-trash')
            ->button()
            ->color('danger')
            ->action(fn (Candidate $record) => $record->delete())
            ->requiresConfirmation()->visible(fn () => RegisteredMember::count() < 0),
            Action::make('restrict_edit')
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->button()
            ->color('success')
            ->action(function () {
                $this->dialog()->error(
                    $title = 'Edit Restriction',
                    $description = 'The registrastration has started and edit is restricted.'
                );
            })->visible(fn () => RegisteredMember::count() > 0),
            Action::make('restrict_delete')
            ->label('Delete')
            ->icon('heroicon-o-trash')
            ->button()
            ->color('danger')
            ->action(function () {
                $this->dialog()->error(
                    $title = 'Delete Restriction',
                    $description = 'The registrastration has started and delete is restricted.'
                );
            })->visible(fn () => RegisteredMember::count() > 0),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('election.name')
            ->label('ELECTION NAME')
            ->formatStateUsing(fn (Candidate $record) => strtoupper($record->election->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('position.name')
            ->label('POSITION')
            ->formatStateUsing(fn (Candidate $record) => strtoupper($record->position?->name))
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('name')
            ->label('NAME')
            ->formatStateUsing(fn (Candidate $record) => strtoupper($record->last_name.' '.$record->first_name.' '.$record->middle_name))
            ->searchable(),
            Tables\Columns\ImageColumn::make('image_path')->label('PHOTO')->size(100)
        ];
    }

    public function render()
    {
        return view('livewire.admin.candidates');
    }
}
