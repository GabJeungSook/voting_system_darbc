<table class="min-w-full border border-gray-600">
    <tbody class="bg-white">
        @foreach ($positions as $item)
        @php
            $voteCounts = $item->candidates->pluck('vote_count')->toArray();
            rsort($voteCounts); // Sort vote counts in descending order
            $rank = 1;
        @endphp
        <tr class="border-t border-gray-600">
            <th class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">No.</th>
            <th class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Rank</th>
            <th class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">{{$item->name}}</th>
            <th class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
        </tr>
        @foreach ($item->candidates->sortBy('last_name') as $candidate)
        <tr class="border-t border-gray-600">
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{$loop->iteration}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                @php
                    $voteIndex = array_search($candidate->vote_count, $voteCounts);
                    echo $voteIndex !== false ? $voteIndex + 1 : '';
                @endphp
            </td>
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{strtoupper($candidate->last_name.', '.$candidate->first_name.' '.$candidate->middle_name)}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{$candidate->vote_count}}
            </td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>

