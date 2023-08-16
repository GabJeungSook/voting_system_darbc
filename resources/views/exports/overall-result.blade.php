<table class="min-w-full border border-gray-600">
    <tbody class="bg-white">
        @foreach ($positions as $item)
        @php
            $counter = 1;
        @endphp
        <tr class="border-t border-gray-600">
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">No.</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">{{$item->name}}</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
        </tr>
        @foreach ($item->candidates->sortByDesc('vote_count') as $candidate)
        <tr class="border-t border-gray-600">
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
               {{$counter++}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name)}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{$candidate->vote_count}}
            </td>
        </tr>
        @endforeach
        @endforeach
        <!-- More people... -->
    </tbody>
</table>
