<div>
    <div class="flex justify-center space-x-1">
      <div class="grid place-content-start">
        <img src="{{ asset('images/darbc.png') }}" class="h-10" alt="">
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-700"> DOLEFIL AGRARIAN REFORM BENEFICIARIES COOP.</h1>
        <h1>DARBC Complex, Brgy. Cannery Site, Polomolok, South Cotabato</h1>
        <h1 class="text-center font-semibold text-md mt-5">{{ $election->name }}</h1>
        <h1 class="text-center font-semibold mt-1">OFFICIAL RESULT</h1>
      </div>

    </div>

    {{-- <h1 class="text-xl mt-5 text-center font-bold text-gray-700">{{strtoupper($sixth_report->header)}}</h1> --}}
    <div class="mt-5 overflow-x-auto">
      {{-- <span class="px-2 text-md font-medium text-gray-500">{{\Carbon\Carbon::now()->format('F d, Y')}}</span> --}}
      {{-- <table id="example" class="table-auto mt-5" style="width:100%">
        <thead class="font-normal">
          <tr>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">NO.
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">MEMBER NAME
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">DEPENDENT NAME
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">DATE OF CONFINEMENT FROM
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">DATE OF CONFINEMENT TO
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">NUMBER OF DAYS
            </th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">AMOUNT
            </th>

          </tr>
        </thead>
        <tbody class="">
            @php
                $count = 1;
            @endphp
          @foreach ($below as $item)
            <tr>

              <td class="border text-gray-600  px-3  py-1">{{$count++}}</td>

               <td class="border text-gray-600 whitespace-nowrap  px-3  py-1">{{ strtoupper($item->first_name).' '.
                strtoupper($item->middle_name).'. '.strtoupper($item->last_name) }}</td>

              <td class="border text-gray-600 whitespace-nowrap  px-3  py-1">{{ strtoupper($item->first_name).' '.
                strtoupper($item->middle_name).'. '.strtoupper($item->last_name) }}</td>


              <td class="border text-gray-600  px-3  py-1">{{ Carbon\Carbon::parse($item->confinement_date_from)->format('F d, Y') }}</td>
              <td class="border text-gray-600  px-3  py-1">{{ Carbon\Carbon::parse($item->confinement_date_to)->format('F d, Y') }}</td>
              <td class="border text-gray-600  px-3  py-1">{{ $item->number_of_days }}</td>
              <td class="border text-gray-600  px-3  py-1">₱{{number_format($item->amount, 2, '.', ',') }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan="6" class="border text-md font-medium text-gray-600  px-3  py-2">TOTAL: </td>
            <td class="border text-md font-medium text-gray-600  px-3  py-2">₱{{number_format($total, 2, '.', ',')}} </td>
          </tr>
        </tbody>
      </table> --}}
      <div class="mt-10 flex justify-around">
        {{-- @foreach ($sixth_signatories as $item)
            <div class="mt-5">
                @if ($item->name == null || $item->name == '')
                <div class="mt-6 w-36 h-0.5 bg-gray-600">
                </div>
                @else
                <span class="font-bold">{{$item->name}}</span>
                @endif
                <h1 class="text-sm">{{$item->position ?? ''}}</h1>
            </div>
        @endforeach --}}
    </div>
    </div>
  </div>
