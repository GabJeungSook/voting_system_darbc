<div>
    <div class="p-5">
        <div>
            <span wire:loading wire:target="uploadAllMembers" class="text-red-600 font-mono mt-3">Uploading Members. Please Wait...</span>
          </div>
        <div wire:loading.remove>
      <div class="flex flex-col border p-2 space-y-1 mb-1">
        <label for="member">Members</label>
        <input type="file" wire:model="members" accept=".csv, .xlsx">
      </div>
      <x-button label="Upload Members" wire:click="uploadAllMembers" class="mt-3" slate spinner="members" />
      </div>
    </div>
    {{-- <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="without_profile">Without Profile</label>
          <input type="file" wire:model="without_profile">
        </div>
        <x-button label="Upload" wire:click="uploadMemberWithoutProfile" dark spinner="without_profile" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">With SPA</label>
          <input type="file" wire:model="with_spa">
        </div>
        <x-button label="Upload" wire:click="uploadMemberWithSPA" dark spinner="with_spa" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">With Garnishment</label>
          <input type="file" wire:model="with_garnishment">
        </div>
        <x-button label="Upload" wire:click="uploadMemberWithGarnishment" dark spinner="with_garnishment" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">With Court Order</label>
          <input type="file" wire:model="with_court_order">
        </div>
        <x-button label="Upload" wire:click="uploadMemberWithCourtOrder" dark spinner="with_court_order" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">Non-Member With Court Order</label>
          <input type="file" wire:model="non_member_w_court_order">
        </div>
        <x-button label="Upload" wire:click="uploadNonMemberWithCourtOrder" dark spinner="non_member_w_court_order" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">Bad Standing</label>
          <input type="file" wire:model="bad_standing">
        </div>
        <x-button label="Upload" wire:click="uploadMemberWithBadStanding" dark spinner="bad_standing" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">HOLD (No Replacement Yet)</label>
          <input type="file" wire:model="no_replacement">
        </div>
        <x-button label="Upload" wire:click="uploadMemberHOLDNoReplacement" dark spinner="no_replacement" />
      </div>
      <div class="p-5">
        <div class="flex flex-col border p-2 space-y-1 mb-1">
          <label for="member">HOLD (With Mediation Proceeding)</label>
          <input type="file" wire:model="w_mediation_proceeding">
        </div>
        <x-button label="Upload" wire:click="uploadMemberHOLDMediationProceeding" dark spinner="w_mediation_proceeding" />
      </div> --}}
  </div>
