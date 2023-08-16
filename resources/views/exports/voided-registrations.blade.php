<table class="min-w-full border border-gray-600">
    <tbody class="bg-white">
        <tr class="border-t border-gray-600">
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">DARBC ID</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Member Name</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">QR Code Claimed By</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Counter</th>
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3 border-r">Note</th>
            {{-- <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Registration Date</th> --}}
            <th  class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">Voided Date</th>

        </tr>
        @foreach ($members as $item)
        <tr class="border-t border-gray-600">
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{$item->member->darbc_id}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{strtoupper($item->registered_member->last_name.', '.$item->registered_member->first_name.' '.$item->registered_member->middle_name)}}
            </td>
            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3 border-r">
                {{strtoupper($item->member->last_name.' '.$item->member->first_name.' '.$item->member->middle_name)}}
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
        @endforeach
    </tbody>
</table>
