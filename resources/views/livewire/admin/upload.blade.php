<div>
    <div>
      <div class="flex flex-col border p-2 space-y-1 mb-1">
        <label for="member">Members</label>
        <input type="file" wire:model="members">
      </div>
      <x-button label="Upload" wire:click="uploadMember" dark spinner="members" />
    </div>
  </div>
