<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use Filament\Forms;
use App\Models\Election;
use App\Models\Member;
use App\Models\RegisteredMember;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Http;
use DB;

class Members extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
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

    public function registerMember()
    {
        $exists = RegisteredMember::where('darbc_member_user_id', $this->member_id)->exists();
        if(!$exists)
        {
            DB::beginTransaction();
            RegisteredMember::create([
               'election_id' => $this->election_id,
               'user_id' => auth()->user()->id,
               'darbc_member_user_id' => $this->member_id,
               'darbc_id' => $this->member_darbc_id,
               'first_name' => $this->member_first_name,
               'middle_name' => $this->member_middle_name,
               'last_name' => $this->member_last_name,
            ]);
            DB::commit();
            $this->dialog()->success(
                $title = 'Member Registered',
                $description = 'User was successfully registered.'
            );
            $this->resets();
            // $this->member_id = null;
            // $this->is_not_changed = false;
        }else{
            $this->dialog()->error(
                $title = 'Operation Faild',
                $description = 'User was already registered.'
            );
            $this->resets();
            // $this->member_id = null;
            // $this->is_not_changed = false;
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
