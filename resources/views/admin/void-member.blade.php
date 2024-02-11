@section('title', 'Void')
<x-main-layout>
    <div x-cloak x-data="{ activeTab: 'void_registration' }">
        <div class="hidden sm:block">
          <nav class="flex space-x-4" aria-label="Tabs">
            <a href="#"
            class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
            :class="{ 'bg-indigo-100 text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab === 'void_registration', 'text-gray-500 hover:text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab !== 'void_registration' }"
            @click.prevent="activeTab = 'void_registration'">Void Registration</a>
            <a href="#" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium"
            :class="{ 'bg-indigo-100 text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab === 'void_voting', 'text-gray-500 hover:text-indigo-700 rounded-md px-3 py-2 text-sm font-medium': activeTab !== 'void_voting' }"
            @click.prevent="activeTab = 'void_voting'">Void Vote</a>
          </nav>
        </div>

        <div x-show="activeTab === 'void_registration'">
            <div class="flex justify-center items-center">
                <div class="relative block mt-3 w-full rounded-lg text-center focus:outline-none">
                    <span class="mt-2 block text-gray-600">
                        <livewire:admin.void-member-registration />
                    </span>
                </div>
            </div>
          </div>

          <div x-show="activeTab === 'void_voting'">
            <div class="flex justify-center items-center">
                <div class="relative block mt-3 w-full rounded-lg text-center focus:outline-none">
                    <span class="mt-2 block text-gray-600">
                        <livewire:admin.void-member-vote />
                    </span>
                </div>
            </div>
          </div>
      </div>
</x-main-layout>
