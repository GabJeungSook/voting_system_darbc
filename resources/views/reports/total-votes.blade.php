<div>
    <div class="flex justify-center space-x-1">
      <div class="grid place-content-start">
        <img src="{{ asset('images/darbc.png') }}" class="h-10" alt="">
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-700"> DOLEFIL AGRARIAN REFORM BENEFICIARIES COOP.</h1>
        <h1>DARBC Complex, Brgy. Cannery Site, Polomolok, South Cotabato</h1>
        <h1 class="text-center font-semibold text-xl mt-5">{{ $election->name }}</h1>
        <h1 class="text-center font-semibold text-lg mt-1">Voter List</h1>
      </div>

    </div>
    <div class="mt-5 overflow-x-auto">
        <div class="inline-block min-w-full pb-4 align-middle sm:px-6 lg:px-3">
            <table class="min-w-full border border-gray-600">
                <tbody class="bg-white">
                    <tr class="border-t border-gray-600">
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">NO.</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Counter</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Voided Votes</th>
                    </tr>
                    @php
                    $counter = 1;
                    @endphp
                    @foreach ($voting_tellers as $item)
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$counter++}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($item->name)}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$item->votes->where('election_id', $election?->id)->pluck('registered_member_id')->unique()->count() + $item->voided_members->count()}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$item->voided_members->count()}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
