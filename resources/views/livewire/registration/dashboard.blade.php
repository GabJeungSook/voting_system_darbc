<div>
    @if ($election_id != null)
    <dl class="mx-auto grid grid-cols-1 gap-x-3 bg-white sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
          <dt class="text-sm text-white font-medium leading-6">My Registered Members</dt>
          <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$registered_count}}</dd>
        </div>
        <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-indigo-800 rounded-md px-4 py-10 sm:px-6 xl:px-8">
            <dt class="text-sm text-white font-medium leading-6">Total Registered Members</dt>
            <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-white">{{$registered_count_total}}</dd>
          </div>
      </dl>
      <div class="py-4">
        {{$this->table}}
      </div>
    @else
    <div class="text-5xl text-red-700">
        No Active Election. Contact Admin to add and activate an election
      </div>
    @endif
</div>
