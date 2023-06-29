<div wire:poll.5s x-data x-animate class="mx-64">
    <div class="flex space-x-2 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-red-700">
            <path fill-rule="evenodd" d="M17.303 5.197A7.5 7.5 0 006.697 15.803a.75.75 0 01-1.061 1.061A9 9 0 1121 10.5a.75.75 0 01-1.5 0c0-1.92-.732-3.839-2.197-5.303zm-2.121 2.121a4.5 4.5 0 00-6.364 6.364.75.75 0 11-1.06 1.06A6 6 0 1118 10.5a.75.75 0 01-1.5 0c0-1.153-.44-2.303-1.318-3.182zm-3.634 1.314a.75.75 0 01.82.311l5.228 7.917a.75.75 0 01-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 01-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 01-1.247-.606l.569-9.47a.75.75 0 01.554-.68z" clip-rule="evenodd" />
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
    @endphp
    @foreach ($sortedCandidates as $candidate)
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 mb-8">
        <div class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm">
            <div class="flex-shrink-0">
                <img class="h-14 w-14 rounded-xl" src="{{$this->getFileUrl($candidate->image_path)}}" alt="">
            </div>
            <div class="flex justify-between flex-grow items-center">
                <div class="focus:outline-none">
                    <p class="text-md font-medium text-gray-900">{{ strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 bg-gray-200 rounded-full text-gray-600 font-bold text-2xl">
                    {{$candidate->votes->count()}}
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endforeach
</div>
