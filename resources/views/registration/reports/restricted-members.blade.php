<div>
    <div class="flex space-x-1">
      <div class="grid place-content-center">
        <img src="{{ asset('images/darbc.png') }}" class="h-10" alt="">
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-700"> DOLEFIL AGRARIAN REFORM BENEFICIARIES COOP.</h1>
        <h1>DARBC Complex, Brgy. Cannery Site, Polomolok, South Cotabato</h1>
      </div>
    </div>
    <div class="mt-10 flex justify-end">
        <span class="text-lg mt-5 text-center font-bold text-gray-700 underline">Total Restricted Members : {{$restricted_members->count()}}</span>
    </div>

    <h1 class="text-xl mt-5 text-center font-bold text-gray-700">RESTRICTED MEMBERS</h1>
    <div class="mt-5 overflow-x-auto">
      <table id="example" class="table-auto mt-5" style="width:100%">
        <thead class="font-normal">
          <tr>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">DARBC ID</th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">FIRST NAME</th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">MIDDLE NAME</th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">LAST NAME</th>
            <th class="border text-left whitespace-nowrap px-2 text-sm font-medium text-gray-500 py-2">RESTRICTION</th>
          </tr>
        </thead>
        <tbody class="">
          @foreach ($restricted_members as $item)
            <tr>
              <td class="border text-gray-600 whitespace-nowrap  px-3  py-1">{{$item->darbc_id}}</td>
              <td class="border text-gray-600 whitespace-nowrap  px-3  py-1">{{strtoupper($item->first_name)}}</td>
              <td class="border text-gray-600  px-3  py-1">{{strtoupper($item->middle_name)}}</td>
              <td class="border text-gray-600  px-3  py-1">{{strtoupper($item->last_name)}}</td>
              <td class="border text-gray-600  px-3  py-1">{{strtoupper($item->restriction)}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
