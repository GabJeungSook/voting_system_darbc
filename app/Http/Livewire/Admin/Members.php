<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class Members extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return Member::query();
    }

    public function getTableActions()
    {
        return [
            Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->button()
            ->color('success')
            ->mountUsing(fn (Forms\ComponentContainer $form, Member $record) => $form->fill([
                'darbc_id' => $record->darbc_id,
                'last_name' => $record->last_name,
                'middle_name' => $record->middle_name,
                'first_name' => $record->first_name,
                'restriction' => $record->restriction,
            ]))
            ->form([
                Forms\Components\TextInput::make('darbc_id')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\TextInput::make('middle_name'),
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\Textarea::make('restriction')->required(),
            ])
            ->action(function (Member $record, array $data): void {
                DB::beginTransaction();
                $record->darbc_id = $data['darbc_id'];
                $record->last_name = $data['last_name'];
                $record->middle_name = $data['middle_name'];
                $record->first_name = $data['first_name'];
                $record->restriction = $data['restriction'];
                $record->save();
                DB::commit();
                $this->dialog()->success(
                    $title = 'Update Success!',
                    $description = 'Data Successfully Updated.'
                );
            }),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('darbc_id')
            ->label('DARBC ID')
            ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
            ->label('LAST NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->last_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
            ->label('MIDDLE NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->middle_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('first_name')
            ->label('FIRST NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->first_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('spa')
            ->label('SPA NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->spa))
            ->searchable(),
            Tables\Columns\BadgeColumn::make('restriction')
            ->label('RESTRICTION')
            ->color('danger')
            ->searchable()->sortable(),
            Tables\Columns\ToggleColumn::make('is_active')
            ->label('Active')
            ->onColor('success')
            ->onIcon('heroicon-s-check')
            ->offIcon('heroicon-s-x')
            ->offColor('danger')
            ->searchable(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.members');
    }
}
