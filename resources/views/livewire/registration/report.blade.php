<div x-data x-animate>
    <div class="flex justify-between">
        <div>
          </div>
        <div class="select flex space-x-2 items-end">
            <x-native-select label="Report" wire:model="report_get">
              <option selected hidden>Select Report</option>
              <option value="1">Restricted Members</option>
            </x-native-select>
            <x-button.circle positive icon="refresh" spinner="report_get" />
          </div>
    </div>

  @if ($report_get)
    <div class="mt-5 flex justify-between items-end">
      <div class="mt-5 flex space-x-2 ">
        <x-button label="PRINT" sm dark icon="printer" class="font-bold"
          @click="printOut($refs.printContainer.outerHTML);" />
        {{-- <x-button label="EXPORT" sm positive wire:click="exportReport({{ $report_get }})"
          spinner="exportReport({{ $report_get }})" icon="document-text" class="font-bold" /> --}}
      </div>
      {{-- @if ($report_get == 1)
        <div class="flex space-x-2">
            <x-select label="Campus" class="w-full" placeholder="All" wire:model="selected_campus">
                @foreach ($campuses as $campus)
                <x-select.option label="{{$campus->name}}" value="{{$campus->id}}" />
                @endforeach
            </x-select>
            <x-select label="Course" class="w-full" placeholder="All" wire:model="selected_course">
               @foreach ($courses as $course)
                <x-select.option label="{{$course->name}}" value="{{$course->id}}" />
                @endforeach
            </x-select>
        </div>
      @endif --}}
    </div>
  @endif
  <div class="mt-5 border rounded-lg p-4" x-ref="printContainer">
    @switch($report_get)
      @case(1)
        @include('registration.reports.restricted-members')
      @break
      @default
        <h1 class="text-gray-600">Select report to generate.</h1>
      @break
    @endswitch
  </div>
</div>
