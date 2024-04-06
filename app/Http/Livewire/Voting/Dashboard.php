<?php

namespace App\Http\Livewire\Voting;

use App\Models\Vote;
use Filament\Tables;
use Livewire\Component;
use App\Models\Election;
use App\Models\Position;
use Mike42\Escpos\Printer;
use App\Models\RegisteredMember;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use WireUi\Traits\Actions;

class Dashboard extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    public $election_id;
    public $election;
    public $positions;
    public $voter_count;
    public $voter_count_total;

    public function testPrinter()
    {
        try {
            $this->test();
        } catch (\Exception $e) {
            $this->dialog()->error(
                $title = 'Printer Disconnected',
                $description = 'Printer is not connected. Please check the printer connection.'
            );
        }

    }


    public function test()
    {
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $connector = new NetworkPrintConnector($printerIp);
        // $name = auth()->user()->name;
        // $connector = new WindowsPrintConnector("EPSON-".$name);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("DARBC 2024 ELECTION\n");
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


    protected function getTableQuery(): Builder
    {
        return RegisteredMember::query()->where('election_id', $this->election_id)->whereHas('votes', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function printBallot($member)
    {
        $reg_member = $member;
        $votes = $reg_member->votes()->get();
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        // $name = auth()->user()->name;
        // $connector = new WindowsPrintConnector("EPSON-".$name);
        $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("DARBC ELECTION 2023\n");
            $printer -> text("OFFICIAL BALLOT\n");
            $printer -> feed(2);
            $printer -> text(\Carbon\Carbon::parse(now())->format('F d, Y')."\n");
            $printer -> text(\Carbon\Carbon::parse(now())->format('h:i:s A')."\n");
            $printer -> feed(3);
            foreach($this->positions as $position)
            {
                $printer -> text(strtoupper($position->name)."\n");

                  // Retrieve the votes for the current position and member
                // $votes = $member->votes()->where('position_id', $position->id)->get();

                 // Print the candidates for the position
                    foreach ($votes as $vote) {
                        if ($vote->position_id == $position->id)
                        {
                            $printer->text(strtoupper($vote->candidate?->first_name.' '.$vote->candidate?->middle_name.' '.$vote->candidate?->last_name) . "\n");
                        }
                    }
                    $printer->feed(2);
            }

            $printer -> feed(4);
            $printer -> text($member_name);
            $printer -> feed(2);
            $printer -> cut();
            $printer -> close();
        } finally {
            $printer -> close();
        }
    }

    public function printCoupon($member)
    {
        $reg_member = $member;
        $votes = $reg_member->votes()->get();
        $printerIp = auth()->user()->printer->ip_address;
        $printerPort = 9100;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        // $name = auth()->user()->name;
        // $connector = new WindowsPrintConnector("EPSON-".$name);
         $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> setEmphasis(true);
            $printer -> text("DARBC ELECTION 2023\n");
            $printer -> text("VOTERS COUPON\n");
            $printer -> setEmphasis(false);
            $printer -> feed(1);
            $printer -> text(\Carbon\Carbon::parse(now())->format('F d, Y')."\n");
            $printer -> text(\Carbon\Carbon::parse($reg_member->registration_duration->time_start)->format('h:i:s A')." - ".\Carbon\Carbon::parse($reg_member->registration_duration->time_end)->format('h:i:s A'));
            $printer -> feed(3);

            $printer -> setEmphasis(true);
            $printer -> text($member_name);
            $printer -> feed(1);
            $printer -> text("VOTED AT ". strtoupper(auth()->user()->name));
            $printer -> setEmphasis(false);
            $printer -> feed(2);
            $printer -> cut();
            $printer -> close();
        } finally {
            $printer -> close();
        }
    }

    public function getTableActions()
    {
        return [
            Action::make('reprint_ballot')
            ->label('Print Ballot')
            ->icon('heroicon-o-printer')
            ->button()
            ->color('warning')
            ->requiresConfirmation()
            ->action(function (RegisteredMember $record) {
                $this->printBallot($record);
                sleep(1);
                $this->printCoupon($record);

            })
        ];
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
        $this->election = Election::where('is_active', true)->first();
        $this->election_id = Election::where('is_active', true)->first()?->id;
        $this->positions = Position::where('election_id', $this->election->id)->get();
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
