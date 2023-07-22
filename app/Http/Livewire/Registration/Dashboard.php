<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Election;
use App\Models\RegisteredMember;
use Filament\Tables\Actions\Action;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class Dashboard extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    public $election_id;
    public $registered_count;
    public $registered_count_total;

    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('election_id', $this->election_id)->where('user_id', auth()->user()->id);
    }

    public function testPrinter()
    {
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $connector = new NetworkPrintConnector($printerIp);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("DARBC 2023 ELECTION\n");
            $printer->text(auth()->user()->name);
            $printer->feed(4);
            $printer->text("Printer is good to go!");
            $printer->feed(4);
            $printer->cut();
            $printer->close();
        } finally {
            $printer -> close();
        }
    }

    public function printQR($member)
    {
        $reg_member = $member;
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $content = $reg_member->qr_code;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        $ec = Printer::QR_ECLEVEL_L;
        $size = 8;
        $model = Printer::QR_MODEL_2;
        $connector = new NetworkPrintConnector($printerIp);
        $printer = new Printer($connector);

        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            // Add border and make the text bold
            $printer->setEmphasis(true);
            $printer->text("DARBC ELECTION 2023\n");
            // $printer->setEmphasis(false);

            $printer->text($member_name);
            $printer->feed(4);
            $printer->qrCode($content, $ec, $size, $model);
            $printer->text($content);
            $printer->feed(4);
            $printer->cut();
            $printer->close();
        } finally {
            $printer->close();
        }
    }

    // public function getTableActions()
    // {
    //     return [
    //         Action::make('reprint_qr')
    //         ->label('Print QR Code')
    //         ->icon('heroicon-o-printer')
    //         ->button()
    //         ->color('warning')
    //         ->requiresConfirmation()
    //         ->action(function (RegisteredMember $record) {
    //             $this->printQR($record);
    //         })
    //     ];
    // }

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
        $this->registered_count_total = RegisteredMember::where('election_id', $this->election_id)->count();
    }

    public function render()
    {
        return view('livewire.registration.dashboard');
    }
}
