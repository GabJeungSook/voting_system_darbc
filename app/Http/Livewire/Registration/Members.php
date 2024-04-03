<?php

namespace App\Http\Livewire\Registration;
use DB;
use Filament\Tables;
use Filament\Forms;
use App\Models\Member;
use Livewire\Component;
use App\Models\Election;
use Mike42\Escpos\Printer;
use WireUi\Traits\Actions;
use Mike42\Escpos\EscposImage;
use App\Models\RegisteredMember;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Members extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Actions;

    public $election_id;
    public $member_id;
    public $member_full_names;
    public $member_details;
    public $member_darbc_id;
    public $member_first_name;
    public $member_middle_name;
    public $member_last_name;
    public $member_restriction;
    public $is_not_changed = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('member_id')
            ->label('DARBC Member')
            ->reactive()
            ->preload()
            ->searchable()
            ->reactive()
            ->afterStateUpdated(fn ($set) => $this->is_not_changed = false)
            ->options(Member::pluck('full_name', 'id'))
            // ->options($this->member_full_names->pluck('full_name', 'id'))
            ->required()
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Member::query();
        // return Member::query()->orderBy('restriction', 'asc');
    }

    public function checkPrinterConnection()
    {
        try{
            // $printerIp = auth()->user()->printer->ip_address;
            // $printerPort = 9100;
            $name = auth()->user()->name;
            $connector = new WindowsPrintConnector("EPSON-".$name);
            // $connector = new NetworkPrintConnector($printerIp);
            $printer = new Printer($connector);
                try {
                    // $printer -> text("DARBC ELECTION 2024\n");
                    // $printer -> feed(1);
                    // $printer -> cut();
                    //$printer -> close();

                    return true;
                } catch(\Exception $e)
                {
                return false;
                }finally {
                    $printer -> close();
                }
        }catch(\Exception $e)
        {
            return false;
        }
    }

    public function getTableActions()
    {
        return [
            Action::make('register')
            ->icon('heroicon-o-check')
            ->button()
            ->color('success')
            ->action(function (Member $record, array $data): void {
                $exists = RegisteredMember::where('member_id', $record->id)->where('is_voided', false)->exists();
                if(!$exists)
                {
                    if($this->checkPrinterConnection())
                    {
                        DB::beginTransaction();
                        $member = RegisteredMember::create([
                           'election_id' => $this->election_id,
                           'user_id' => auth()->user()->id,
                           'member_id' => $record->id,
                           'darbc_id' =>  $record->darbc_id,
                           'first_name' => $record->first_name,
                           'middle_name' =>$record->middle_name,
                           'last_name' => $record->last_name,
                           'qr_code' => uniqid(),
                        ]);
                        DB::commit();
                        $this->dialog()->success(
                            $title = 'Member Registered',
                            $description = 'User was successfully registered.'
                        );
                        $this->resets();
                        $this->printQR($member);
                    }else{
                        $this->dialog()->error(
                            $title = 'Printer Disconnected',
                            $description = 'Printer is not connected. Please check the printer connection.'
                        );
                    }

                }else{
                    $this->dialog()->error(
                        $title = 'Operation Faild',
                        $description = 'User was already registered.'
                    );
                    $this->resets();
                }
            })->requiresConfirmation()->visible(function ($record) {
                $exists = RegisteredMember::where('member_id', $record->id)->where('is_voided', false)->exists();
                if(!$exists && $record->is_active === 1)
                {
                    return true;
                }else
                {
                    return false;
                }
            }),
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
            ->formatStateUsing(fn (Member $record) => strtoupper($record->first_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
            ->label('MIDDLE NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->middle_name))
            ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
            ->label('LAST NAME')
            ->formatStateUsing(fn (Member $record) => strtoupper($record->last_name))
            ->searchable(),
            Tables\Columns\BadgeColumn::make('restriction')
            ->label('RESTRICTION')
            ->color('danger')
            ->searchable()->sortable(),
        ];
    }

    public function resets()
    {
        $this->member_id = null;
        $this->is_not_changed = false;
    }

    public function showData()
    {
        if($this->member_id != null)
        {
            $this->member_details = Member::find($this->member_id);

            $this->is_not_changed = true;
            $this->member_darbc_id =  $this->member_details->darbc_id;
            $this->member_first_name =  $this->member_details->first_name;
            $this->member_middle_name =  $this->member_details->middle_name;
            $this->member_last_name =  $this->member_details->last_name;
            $this->member_darbc_id =  $this->member_details->darbc_id;
           // $this->member_details = $this->getDataFromAPI($this->member_id);
            // $this->member_darbc_id =  $this->member_details['darbc_id'];
            // $this->member_first_name = $this->member_details['user']['first_name'];
            // $this->member_middle_name = $this->member_details['user']['middle_name'];
            // $this->member_last_name =  $this->member_details['user']['surname'];
            $restriction = trim($this->member_details->restriction);
            if (empty($restriction)) {
                $this->member_restriction = null;
            }else{
                $this->member_restriction =  $restriction;
            }
        }
    }

    public function printQR($member)
    {
        $reg_member = $member;
        // $printerIp = auth()->user()->printer->ip_address;
        // $printerPort = 9100;
        $content = $reg_member->qr_code;
        $member_name = strtoupper($reg_member->first_name.' '.$reg_member->middle_name.' '.$reg_member->last_name);
        $counter_number = $member->user->name;
        $ec = Printer::QR_ECLEVEL_L;
        $size = 8;
        $model = Printer::QR_MODEL_2;
        // $img = EscposImage::load(Storage::url('images/darbc.png'));
        $name = auth()->user()->name;
        $connector = new WindowsPrintConnector("EPSON-".$name);
        // $connector = new NetworkPrintConnector($printerIp);
        $printer = new Printer($connector);
        try {
            if($printer)
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> graphics($img, 2);
            $printer -> text("DARBC ELECTION 2023\n");
            $printer -> text($member_name);
            $printer -> feed(1);
            $printer -> text($counter_number);
            $printer -> feed(4);
            $printer -> qrCode($content, $ec, $size, $model);
            $printer -> text($content);
            $printer -> feed(4);
            $printer -> cut();
            //$printer -> close();
        } catch(\Exception $e)
        {
            Notification::make()
            ->title($e->getMessage())
            ->success()
            ->send();
        }finally {
            $printer -> close();
        }
    }

    public function registerMember()
    {

        $exists = RegisteredMember::where('member_id', $this->member_id)->where('is_voided', false)->exists();
        if(!$exists)
        {
            DB::beginTransaction();
            $member = RegisteredMember::create([
               'election_id' => $this->election_id,
               'user_id' => auth()->user()->id,
               'member_id' => $this->member_id,
               'darbc_id' => $this->member_darbc_id,
               'first_name' => $this->member_first_name,
               'middle_name' => $this->member_middle_name,
               'last_name' => $this->member_last_name,
               'qr_code' => uniqid(),
            ]);
            DB::commit();
            $this->dialog()->success(
                $title = 'Member Registered',
                $description = 'User was successfully registered.'
            );
            $this->resets();
            $this->printQR($member);
        }else{
            $this->dialog()->error(
                $title = 'Operation Faild',
                $description = 'User was already registered.'
            );
            $this->resets();
        }

    }

    public function render()
    {
        return view('livewire.registration.members');
    }

    // public function getDataFromAPI($id)
    // {
    //     $url = 'https://darbc.org/api/member-information/'.$id;
    //     $response = file_get_contents($url);
    //     $member_data = json_decode($response, true);

    //     return collect($member_data['data']);
    // }

    // public function getDataFromAPI($id, $page = 1, $perPage = 10)
    // {
    //     $url = "https://darbc.org/api/member-information/{$id}?page={$page}&per_page={$perPage}";
    //     $response = Http::get($url);
    //     $member_data = $response->json();

    //     return collect($member_data['data']);
    // }

    // public function getDataFromAPI($id)
    // {
    //     $url = 'https://darbc.org/api/member-information/'.$id;
    //     $response = Http::get($url);
    //     $member_data = json_decode($response, true);

    //     return collect($member_data['data']);
    // }


    // public function getNamesFromAPI()
    // {
    //     $url = 'https://darbc.org/api/member-darbc-names';
    //     $response = file_get_contents($url);
    //     $member_data = json_decode($response, true);

    //     return collect($member_data);
    // }

    // public function getNamesFromAPI()
    // {
    //     $url = 'https://darbc.org/api/member-darbc-names';
    //     $response = Http::get($url);
    //     $member_data = $response->json();

    //     return collect($member_data);
    // }

    public function mount(): void
    {

        $this->election_id = Election::where('is_active', true)->first()?->id;
      // $this->member_full_names = $this->getNamesFromAPI();
    }
}
