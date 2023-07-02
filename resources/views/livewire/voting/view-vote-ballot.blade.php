<div x-data>
    <div class="mx-64">
        <div class="flex justify-between mb-4">
            <x-button positive icon="arrow-left" label="Back" wire:click="returnToVotingIndex" spinner="returnToVotingIndex" />
            <x-button secondary icon="printer" label="Print" @click="printOut($refs.printContainer.outerHTML);"/>
        </div>
        <div class="divide-y divide-black overflow-hidden bg-white shadow  border border-gray-500" x-ref="printContainer">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex space-x-1 justify-center">
                    <div class="grid place-content-start">
                        <img src="{{ asset('images/darbc.png') }}" class="xl:h-10 xl:w-10 sm:h-8 sm:w-16" alt="">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-700 sm:text-xs lg:text-sm xl:text-xl">DOLEFIL AGRARIAN REFORM BENEFICIARIES COOP.</h1>
                        <h1 class=" lg:text-xs sm:text-xs xl:text-sm text-center">DARBC Complex, Brgy. Cannery Site, Polomolok, South Cotabato</h1>
                        <h1 class="text-2xl text-center font-bold text-gray-700 mt-5">OFFICIAL BALLOT</h1>
                    </div>

                </div>
                <div class="flex justify-end">
                    <span class=" font-mono lg:text-xs xl:text-sm">Date: {{ \Carbon\Carbon::parse(now())->format('F d, Y') }}</span>
                </div>
            </div>
            <div class="p-1">
                <span class="text-sm font-mono">MEMBER NAME:  {{strtoupper($record->first_name.' '.$record->middle_name.' '.$record->last_name)}}</span>
            </div>
            <div class="p-2">
                <span class="text-sm font-mono">DARBC ID: {{$record->darbc_id}}</span>
            </div>
            <div class="p-2">
                <span class="text-sm font-mono">ELECTION: {{$election->name}}</span>
            </div>
            <div class="p-2">
                <span class="text-sm font-mono">ELECTION DATE: {{\Carbon\Carbon::parse($election->date_from)->format('F d, Y')}} - {{\Carbon\Carbon::parse($election->date_to)->format('F d, Y')}}</span>
             </div>
            <div class="px-4 py-3 sm:p-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="mt-4 flow-root">
                      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full pb-4 align-middle sm:px-6 lg:px-3">
                            <table class="min-w-full border border-gray-600">
                                <tbody class="bg-white">
                                    @foreach ($positions as $item)
                                    <tr class="border-t border-gray-600">
                                        <th colspan="5" scope="colgroup" class="bg-indigo-800 py-2 pl-4 pr-3 text-left text-sm uppercase font-semibold text-white sm:pl-3">{{$item->name}}</th>
                                    </tr>
                                    @foreach ($votes as $vote)
                                    @if ($vote->position_id == $item->id)
                                    <tr class="border-t border-gray-600">
                                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-sm font-medium text-gray-900 sm:pl-3">
                                            {{strtoupper($vote->candidate?->first_name.' '.$vote->candidate?->middle_name.' '.$vote->candidate?->last_name)}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    <!-- More people... -->
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-10 flex justify-end mr-4 items-center">
                    <div class="grid grid-cols-1">
                         <div class="text-center font-mono ">
                        {{strtoupper($record->first_name.' '.$record->middle_name.' '.$record->last_name)}}
                        </div>
                        <div class="col-span-1 t-6 w-56 h-0.5 bg-gray-800"></div>
                        <div class="col-span-1 font-mono text-center">
                            Signature
                          </div>
                    </div>


                  </div>
            </div>
          </div>

    </div>



</div>
