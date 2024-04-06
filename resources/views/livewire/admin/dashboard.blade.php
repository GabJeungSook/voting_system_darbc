<div>
    <x-button red icon="eye" label="View Live Result" wire:click="redirectToLiveResult" />
    <x-button slate icon="download" label="Backup Database" wire:click="backupCompleted" />
    <x-button emerald icon="printer" label="Test Printer" wire:click="testPrinter" />
      <div class="mt-5">
        <h2 class="text-sm font-medium text-gray-500">Registered Members Per Staff</h2>
        <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            @foreach ($tellers as $item)
          <li class="col-span-1 flex rounded-md shadow-sm">
            <div class="flex w-16 flex-shrink-0 items-center justify-center bg-indigo-600 rounded-l-md text-sm font-medium text-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                  </svg>
            </div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                <div class="flex-1 truncate px-4 py-4 text-sm">
                  <a href="#" class="font-medium text-gray-900 hover:text-gray-600">{{strtoupper($item->name)}}</a>
                </div>
                <div class="">
                    <p class="text-gray-800 text-lg pr-4">{{$item->registered_members->where('election_id', $election?->id)->where('is_voided', false)->count()}}</p>
                  </div>
              </div>

          </li>
          @endforeach
        </ul>
      </div>

      <div class="mt-5">
        <h2 class="text-sm font-medium text-gray-500">Successful Voters Per Staff</h2>
        <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            @foreach ($voting_tellers as $item)
          <li class="col-span-1 flex rounded-md shadow-sm">
            <div class="flex w-16 flex-shrink-0 items-center justify-center bg-indigo-600 rounded-l-md text-sm font-medium text-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                  </svg>
            </div>
            <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                <div class="flex-1 truncate px-4 py-4 text-sm">
                  <a href="#" class="font-medium text-gray-900 hover:text-gray-600">{{strtoupper($item->name)}}</a>
                </div>
                <div class="py-2">
                    <div class="py-1">
                        <p class="text-gray-800 text-sm pr-4">Total Votes: {{$item->votes->where('election_id', $election?->id)->pluck('registered_member_id')->unique()->count() + $item->voided_members->count()}}</p>
                      </div>
                      <div class="py-1">
                        <p class="text-gray-800 text-sm pr-4"> Voided Votes: {{$item->voided_members->count()}}</p>
                      </div>
                </div>

              </div>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="grid grid-cols-4 gap-3">
        <div class="bg-gray-900 mt-5">
            <dl class="mx-auto grid grid-cols-1 gap-x-3 bg-white ">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
                  <dt class="text-sm text-white font-medium leading-6">Total Registered Members</dt>
                  <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$total_registered_members}}</dd>
                </div>
              </dl>
          </div>
          <div class="bg-gray-900 mt-5">
            <dl class="mx-auto grid grid-cols-1 gap-x-3 bg-white">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
                  <dt class="text-sm text-white font-medium leading-6">Total Overall Votes</dt>
                  <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$total_overall_votes}}</dd>
                </div>
              </dl>
          </div>
          <div class="bg-gray-900 mt-5">
            <dl class="mx-auto grid grid-cols-1 gap-x-3 bg-white">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
                  <dt class="text-sm text-white font-medium leading-6">Total Successful Voters</dt>
                  <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$total_voters}}</dd>
                </div>
              </dl>
          </div>
          <div class="bg-gray-900 mt-5">
            <dl class="mx-auto grid grid-cols-1 gap-x-3 bg-white">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
                  <dt class="text-sm text-white font-medium leading-6">Total Voided Votes</dt>
                  <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$total_voided_votes}}</dd>
                </div>
              </dl>
          </div>
      </div>

</div>
