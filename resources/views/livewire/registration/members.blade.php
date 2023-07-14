<div>
    @if ($election_id != null)
    {{$this->table}}
   {{-- {{$this->form}}
   <div class="my-3">
    <x-button class="w-full" indigo label="Submit" wire:click="showData" spinner="showData" />
   </div>
   <div class="overflow-hidden rounded-lg bg-gray-200">
    <div x-data x-animate class="px-4 py-5 sm:p-6">
        @if ($member_details && $is_not_changed == true)
        <x-input wire:model="member_darbc_id" disabled class="my-3" label="DARBC ID" placeholder="" />
        <x-input wire:model="member_first_name" disabled class="my-3" label="FIRST NAME" placeholder="" />
        <x-input wire:model="member_middle_name" disabled class="my-3" label="MIDDLE NAME" placeholder="" />
        <x-input wire:model="member_last_name" disabled class="my-3" label="LAST NAME" placeholder="" />
        @if ($member_restriction != null)
        <x-input wire:model="member_restriction" disabled class="my-3" label="RESTRICTION" placeholder="" />
        <div class="flex justify-end">
        <x-button negative label="Cancel" wire:click="resets"/>
        </div>
        @else
        <div class="flex justify-end">
            <x-button positive label="Register"
            x-on:confirm="{
                title: 'Are you sure you want to register this member?',
                icon: 'warning',
                method: 'registerMember'
            }"/>
        </div>
        @endif

        @elseif($member_details && $is_not_changed == false)
        <div class="text-center font-semi-bold text-gray-500">
            MEMBER DATA WILL BE DISPLAYED HERE
        </div>
        @endif

      <!-- Content goes here -->
    </div>
  </div> --}}
  @else
  <div class="text-5xl text-red-700">
    No Active Election. Contact Admin to add and activate an election
  </div>
  @endif
</div>
