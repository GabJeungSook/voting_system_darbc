<div x-data x-animate>
    <div class="flex justify-between">
        <div>
            <div>
                <x-button label="Back" class="font-bold" icon="arrow-left" positive  wire:click="redirectToDashboard" />

              </div>
          </div>
        <div class="select flex space-x-2 items-end">
            <x-native-select label="Counter" wire:model="selectedCounter">
                <option value="" selected>All</option>
                @foreach ($counter as $item)
                <option value={{$item->id}}>{{strtoupper($item->name)}}</option>
                @endforeach
              </x-native-select>
            <x-button label="Print" class="font-bold" icon="printer" secondary  @click="printOut($refs.printContainer.outerHTML);" />
          </div>
    </div>
  <div class="mt-5 border rounded-lg p-4" x-ref="printContainer">
    @if ($election != null)
    @include('reports.overall-result', ['election' => $election])
    @else
    No Active Election!
    @endif
  </div>
</div>
