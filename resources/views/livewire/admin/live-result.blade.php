<div wire:poll.5s x-data x-animate class="mx-64">
    <div class="flex space-x-2 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-red-700">
            <!-- SVG code for the icon -->
        </svg>
        <h1 class="text-3xl font-semibold">Live Result</h1>
    </div>
    @foreach ($positions as $item)
    <div class="flex flex-col">
        <span class="text-center font-bold text-xl my-3">{{ strtoupper($item->name) }}</span>
    </div>
    @php
        $sortedCandidates = $item->candidates->sortByDesc(function ($candidate) {
            return $candidate->votes->count();
        });
        $ranking = 1; // Initialize the ranking variable to 1
    @endphp
    @foreach ($sortedCandidates as $candidate)
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 mb-8">
        <div class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm">
            <div class="flex items-center justify-center bg-green-200 rounded-full text-gray-600 font-bold text-2xl w-14 h-14">
                {{ $ranking }}
            </div>
            <img class="h-14 w-14 rounded-xl ml-3" src="{{ $this->getFileUrl($candidate->image_path) }}" alt="">
            <div class="flex justify-between flex-grow items-center">
                <div class="focus:outline-none">
                    <p class="text-md font-medium text-gray-900">{{ strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 bg-gray-200 rounded-full text-gray-600 font-bold text-2xl">
                    {{ $candidate->votes->count() }}
                </div>
            </div>
        </div>
    </div>
    @php
        $ranking++; // Increment the ranking for the next candidate
    @endphp
    @endforeach
    @endforeach
</div>
