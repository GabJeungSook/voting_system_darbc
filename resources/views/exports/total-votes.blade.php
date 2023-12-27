<table class="min-w-full border border-gray-600">
    <tbody class="bg-white">
        <tr class="border-t border-gray-600">
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">NO.</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Counter</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Total Votes</th>
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
                {{$item->votes->where('election_id', $election?->id)->pluck('registered_member_id')->unique()->count()}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
