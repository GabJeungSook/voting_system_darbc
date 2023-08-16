<table class="min-w-full border border-gray-600">
    <tbody class="bg-white">
        <tr class="border-t border-gray-600">
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">NO.</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">DARBC ID</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Member Name</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Counter</th>
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
    </tbody>
</table>
