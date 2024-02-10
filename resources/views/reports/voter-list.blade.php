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
    <div class="">
        {{-- add total count --}}
        <h1 class="text-left px-5 font-semibold text-lg mt-1">Total Ballot Count: {{ $members->count() + $voided_members->count() }}</h1>
        <h1 class="text-left px-5 font-semibold text-lg mt-1">Total Void Count: {{ $voided_members->count() }}</h1>
    </div>
    <div class="mt-5 overflow-x-auto">

        <div class="inline-block min-w-full pb-4 align-middle sm:px-6 lg:px-3">
            <table class="min-w-full border border-gray-600">
                <tbody class="bg-white">
                    <tr class="border-t border-gray-600">
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">NO.</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">DARBC ID</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Member Name</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Voted At</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Voted Date</th>
                    </tr>
                    @php
                    $counter = 1;
                    @endphp
                    @foreach ($members as $item)
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$counter++}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$item->member->darbc_id}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($item->last_name.', '.$item->first_name.' '.$item->middle_name)}}
                        </td>

                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($item->vote->user->name)}}
                        </td>

                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{\Carbon\Carbon::parse($item->registration_duration->updated_at)->format('F d, Y h:i:s A')}}
                        </td>
                    </tr>
                    @endforeach
                    {{-- @foreach ($positions as $item)
                    <tr class="border-t border-gray-600">
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">{{$item->name}}</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
                    </tr>
                    @foreach ($item->candidates->sortByDesc('vote_count')->take($item->number_of_winners) as $candidate)
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name)}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$candidate->votes()->count()}}
                        </td>
                    </tr>
                    @endforeach
                    @endforeach --}}
                    <!-- More people... -->
                </tbody>
            </table>
        </div>
        <div class="flex justify-center space-x-1">
            <div class="grid place-content-start">
              {{-- <img src="{{ asset('images/darbc.png') }}" class="h-10" alt=""> --}}
            </div>
            <div>
              <h1 class="ml-10 font-semibold text-lg mt-1">Voided List</h1>
            </div>

          </div>
        <div class="mt-5 inline-block min-w-full pb-4 align-middle sm:px-6 lg:px-3">
            <table class="min-w-full border border-gray-600">
                <tbody class="bg-white">
                    <tr class="border-t border-gray-600">
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">NO.</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">DARBC ID</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Member Name</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Voted At</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Note</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Voided Date</th>
                    </tr>
                    @php
                    $counter = 1;
                    @endphp
                    @forelse ($voided_members as $item)
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$counter++}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$item->registered_member->darbc_id}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            @if ($item->member_id === null)
                            UNIDENTIFIED
                            @else
                            {{strtoupper($item->member->last_name.', '.$item->member->first_name.' '.$item->member->middle_name)}}
                            @endif
                        </td>

                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($item->user->name)}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($item->note)}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{\Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i:s A')}}
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            No data available
                        </td>
                    </tr>
                    @endforelse
                    {{-- @foreach ($positions as $item)
                    <tr class="border-t border-gray-600">
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">{{$item->name}}</th>
                        <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
                    </tr>
                    @foreach ($item->candidates->sortByDesc('vote_count')->take($item->number_of_winners) as $candidate)
                    <tr class="border-t border-gray-600">
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name)}}
                        </td>
                        <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                            {{$candidate->votes()->count()}}
                        </td>
                    </tr>
                    @endforeach
                    @endforeach --}}
                    <!-- More people... -->
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
