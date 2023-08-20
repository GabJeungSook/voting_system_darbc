<div>
    <div class="flex justify-center space-x-1">
      <div class="grid place-content-start">
        <img src="{{ asset('images/darbc.png') }}" class="h-10" alt="">
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-700"> DOLEFIL AGRARIAN REFORM BENEFICIARIES COOP.</h1>
        <h1>DARBC Complex, Brgy. Cannery Site, Polomolok, South Cotabato</h1>
        <h1 class="text-center font-semibold text-xl mt-5">{{ $election->name }}</h1>
        <h1 class="text-center font-semibold text-lg mt-1">OVERALL RESULT</h1>
      </div>

    </div>
    <div class="mt-5 overflow-x-auto">
        <div class=" min-w-full pb-4 align-middle sm:px-6 lg:px-3">
            @php
            // Sort candidates by vote count in descending order
            $sortedCandidates = $item->candidates->sortByDesc('vote_count');
            $rank = 1;
            $previousVoteCount = PHP_INT_MAX; // Initialize with a large value
        @endphp

        <table class="min-w-full border border-gray-600">
            <tbody class="bg-white">
                @foreach ($sortedCandidates as $candidate)
                <tr class="border-t border-gray-600">
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                        {{$rank}}
                        @if ($candidate->vote_count < $previousVoteCount)
                            @php $rank++; @endphp
                        @endif
                        @php $previousVoteCount = $candidate->vote_count; @endphp
                    </td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                        {{strtoupper($candidate->last_name.', '.$candidate->first_name.' '.$candidate->middle_name)}}
                    </td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                        {{$candidate->vote_count}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
      <div class="mt-10 flex justify-around">
    </div>
    </div>
  </div>
