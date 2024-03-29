<div x-data x-animate>
    <div class="flex justify-between">
        <div>
            <div>
                <x-button label="Back" class="font-bold" icon="arrow-left" positive  wire:click="redirectToDashboard" />

              </div>
          </div>
        <div class="select flex space-x-2 items-end">
            <x-native-select label="Voting Counter" wire:model="selectedCounter">
                <option value="" selected>All</option>
                @foreach ($counter as $item)
                <option value={{$item->id}}>{{strtoupper($item->name)}}</option>
                @endforeach
              </x-native-select>
              <x-input wire:model="member_name" label="Search" placeholder="Member's Name" />
            <x-button label="Print" class="font-bold" icon="printer" secondary  @click="printOut($refs.printContainer.outerHTML);" />
            <x-button label="Export Excel" class="font-bold" icon="document-download" positive  wire:click="exportReport" />
          </div>
    </div>
  <div class="mt-5 border rounded-lg p-4" x-ref="printContainer">
    @if ($election != null)
    @include('reports.voter-list', ['election' => $election])
    @else
    No Active Election!
    @endif
  </div>
</div>
