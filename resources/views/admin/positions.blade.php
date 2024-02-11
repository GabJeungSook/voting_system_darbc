@section('title', 'Manage Positions')
<x-main-layout>
    <div x-cloak x-data="{ activeTab: 'candidate_number' }">
        <div class="hidden sm:block">
          <nav class="flex space-x-4" aria-label="Tabs">
            <a href="#"
            class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
            :class="{ 'bg-indigo-100 text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab === 'positions', 'text-gray-500 hover:text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab !== 'positions' }"
            @click.prevent="activeTab = 'positions'">Positions</a>
            <a href="#" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
            :class="{ 'bg-indigo-100 text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab === 'candidate_number', 'text-gray-500 hover:text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab !== 'candidate_number' }"
            @click.prevent="activeTab = 'candidate_number'">Number Of Candidates & Winners</a>
          </nav>
        </div>

        <div x-show="activeTab === 'positions'">
            <div class="flex justify-center items-center">
                <div class="relative block mt-3 w-full rounded-lg text-center focus:outline-none">
                    <span class="mt-2 block text-gray-600">
                          {{-- <livewire:settings.manage-users /> --}}
                    </span>
                </div>
            </div>
          </div>

          <div x-show="activeTab === 'candidate_number'">
            <div class="flex justify-center items-center">
                <div class="relative block mt-3 w-full rounded-lg text-center focus:outline-none">
                    <span class="mt-2 block text-gray-600">
                        <livewire:admin.positions />
                    </span>
                </div>
            </div>
          </div>
      </div>

</x-main-layout>
