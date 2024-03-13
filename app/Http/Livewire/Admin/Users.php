<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use DB;

class Users extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    protected function getTableQuery(): Builder
    {
        return User::query()->where('role_id', '!=', 1);
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
            ->label('Add New User')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->action(function (array $data): void {
                if($data['password'] === $data['confirm_password'])
                {
                    DB::beginTransaction();
                    User::create([
                        'role_id' => $data['role_id'],
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);
                    DB::commit();
                    $this->dialog()->success(
                        $title = 'Success',
                        $description = 'Data was successfully saved'
                    );
                }else{
                    Notification::make()
                    ->title('Passwords did not match!')
                    ->body('Please match your passwords.')
                    ->danger()
                    ->send();
                }
            })
            ->form([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('password')->password()->required(),
                Forms\Components\TextInput::make('confirm_password')->password()->required(),
                Forms\Components\Select::make('role_id')
                ->label('User Assignment')
                ->options([
                    2 => 'Registration',
                    3 => 'Voting',
                    4 => 'Elecom Officer'
                ])
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
            ->mountUsing(fn (Forms\ComponentContainer $form, User $record) => $form->fill([
                'role_id' => $record->role_id,
                'name' => $record->name,
                'email' => $record->email,
            ]))
            ->form([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\Select::make('role_id')
                ->label('User Assignment')
                ->options([
                    2 => 'Registration',
                    3 => 'Voting',
                    4 => 'Elecom Officer'
                ])
                ->required(),
                Forms\Components\Fieldset::make('Change Password')
                ->schema([
                    Forms\Components\TextInput::make('old_password')->reactive()->password(),
                    Forms\Components\TextInput::make('new_password')->reactive()->password()->required(fn ($get) => $get('old_password') != null),
                    Forms\Components\TextInput::make('confirm_password')->reactive()->password()->required(fn ($get) => $get('old_password') != null),
                ])->columns(1)
            ])
            ->action(function (User $record, array $data): void {

                if($data['old_password'] != null)
                {
                    if (Hash::check($data['old_password'], $record->password)) {
                        if($data['new_password'] === $data['confirm_password'])
                        {
                            DB::beginTransaction();
                            $record->name = $data['name'];
                            $record->email = $data['email'];
                            $record->password = Hash::make($data['new_password']);
                            $record->save();
                            DB::commit();
                            $this->dialog()->success(
                                $title = 'Success',
                                $description = 'Data was successfully updated'
                            );

                        }else{
                            Notification::make()
                            ->title('Passwords did not match!')
                            ->body('Please match your passwords.')
                            ->danger()
                            ->send();
                        }
                } else {
                    Notification::make()
                    ->title('Old Password did not match!')
                    ->body('Please enter your old password.')
                    ->danger()
                    ->send();
                    $this->dialog()->error(
                        $title = 'Old Password did not match!',
                        $description = 'Please enter your old password.'
                    );
                }
                }else{
                    DB::beginTransaction();
                    $record->role_id = $data['role_id'];
                    $record->name = $data['name'];
                    $record->email = $data['email'];
                    $record->save();
                    DB::commit();
                    $this->dialog()->success(
                        $title = 'Success',
                        $description = 'Data was successfully updated'
                    );
                }
            }),
            Tables\Actions\ActionGroup::make([
                Action::make('reset_password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (User $record, array $data): void {
                    $record->password = Hash::make('12345');
                    $record->save();
                    $this->dialog()->success(
                        $title = 'Password Reset Successfull'
                    );
                }),
                Action::make('delete')
                ->icon('heroicon-o-trash')
                ->button()
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn (User $record) => $record->delete()),

            ])

        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
            ->label('NAME')
            ->formatStateUsing(fn (User $record) => strtoupper($record->name))
            ->searchable(),
            Tables\Columns\TextColumn::make('email')
            ->label('EMAIL')
            ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
            ->formatStateUsing(fn (User $record) => strtoupper($record->getRoleNameAttribute()))
            ->label('ASSIGNMENT')
            ->searchable(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.users');
    }
}
