<div x-data x-animate>
    <div class="flex justify-between">
        <div>
            <div>
                <x-button label="Back" class="font-bold" icon="arrow-left" positive  wire:click="redirectToDashboard" />

              </div>
          </div>
        <div class="select flex space-x-2 items-end">
            <x-button label="Print" class="font-bold" icon="printer" secondary  @click="printOut($refs.printContainer.outerHTML);" />
            <x-button label="Export Excel" class="font-bold" icon="document-download" positive  wire:click="exportReport" />
          </div>
    </div>
  <div class="mt-5 border rounded-lg p-4" x-ref="printContainer">
    @if ($election != null)
    @include('reports.official-result', ['election' => $election])
    @else
    No Active Election!
    @endif
  </div>
</div>
