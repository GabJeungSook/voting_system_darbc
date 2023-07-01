<div>
    <div class="text-lg font-medium">
        Voter: {{strtoupper($record->first_name.' '.$record->middle_name.' '.$record->last_name)}}
    </div>
    @if ($steps === 0)
        <div class="">
            NOPE
        </div>
    @else
        <div>
            @foreach ($positions as $step => $item)
                @if ($currentStep === $step + 1)
                    <div class="text-center">
                        <h1 class="font-semibold text-xl uppercase">{{ $item->name }}</h1>
                        <span class="mt-2 text-md">Maximum of ({{$item->number_of_winners}}) selections.</span>
                    </div>
                    <div class="overflow-hidden rounded-lg bg-gray-200 mt-10">
                        <div class="px-4 py-5 sm:p-6 flex flex-wrap justify-center">
                            <!-- Content goes here -->
                            @foreach ($item->candidates as $candidate)
                                <div class="relative w-1/5 p-2">
                                    <div class="flex flex-col items-center">
                                        <div class="text-center font-semibold text-lg">
                                            <img src="{{$this->getFileUrl($candidate->image_path)}}" alt="" class="mb-3 rounded-lg w-60 h-60 border-gray-400 border-2 transition duration-300 ease-in-out transform hover:scale-110 {{ in_array($candidate->id, $selectedImages[$currentStep]) ? 'border-8 border-green-600' : 'border-gray-400' }}" wire:click="selectImage({{$candidate->id }})">
                                            <span>{{strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name)}}</span>
                                        </div>
                                        @if (in_array($candidate->id, $selectedImages[$currentStep]))
                                            <div class="absolute top-15 right-15 m-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-600">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Include your step {{ $currentStep }} form elements here -->
                @endif
            @endforeach
        </div>

        <div class="flex mt-14">
            @if($currentStep > 1)
                <button wire:click="previousStep" class="flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-3">
                        <path fill-rule="evenodd" d="M13.28 3.97a.75.75 0 010 1.06L6.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0zm6 0a.75.75 0 010 1.06L12.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd" />
                    </svg>
                    Previous
                </button>
            @endif

            <div class="ml-auto">
                @if($currentStep < $steps)
                    <button wire:click="nextStep" class="flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                @endif

                @if($currentStep === $steps)
                    <button wire:click="showBallot" class="flex bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-3">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
                          </svg>
                        Review Vote
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- REVIEW VOTE MODAL --}}
    <x-modal.card title="Review Vote" fullscreen blur wire:model.defer="reviewVoteModal">
        {{-- @dump($selectedCandidates) --}}
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
            </div>
            <div class="mt-8 flow-root">
                <h1 class="text-center text-2xl font-semibold mb-4">{{$election->name}}</h1>
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full">
                            <thead class="bg-white">
                                <!-- Table header content goes here -->
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($positions as $item)
                                    <tr class="border-t border-gray-200">
                                        <th colspan="5" scope="colgroup" class="text-center bg-gray-50 py-2 pl-4 pr-3 text-sm font-semibold text-gray-900 sm:pl-3">{{ strtoupper($item->name) }}</th>
                                    </tr>
                                    <tr class="border-t border-gray-300">
                                        <td colspan="5" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                            <div class="flex justify-center space-x-4">
                                                @foreach ($selectedCandidates as $selectedCandidate)
                                                    @if ($selectedCandidate['position_id'] === $item->id)
                                                        @php
                                                            $candidate = App\Models\Candidate::firstWhere('id', $selectedCandidate['candidate_id']);
                                                        @endphp
                                                        <div class="flex flex-col items-center">
                                                            <img src="{{$this->getFileUrl($candidate->image_path)}}" alt="" class="mb-3 rounded-lg w-56 h-56 border-gray-400 border-2 transition duration-300 ease-in-out transform hover:scale-110" >
                                                            <span class="text-center">{{ strtoupper($candidate->first_name.' '.$candidate->middle_name.' '.$candidate->last_name) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <div class=""></div>
                <div class="flex space-x-3">
                    <x-button slate label="Cancel" x-on:click="close" />
                    <x-button positive icon="check" label="Submit" spinner="saveVote"
                    x-on:confirm="{
                        title: 'Are you sure you want to save your vote?',
                        icon: 'warning',
                        method: 'saveVote',
                        params: 1
                    }" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>
</div>
