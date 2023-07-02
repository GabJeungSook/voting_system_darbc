<div>
    <div class="flex justify-between mb-4">
        <x-button positive icon="arrow-left" label="Back" wire:click="returnToVotingIndex" spinner="returnToVotingIndex" />
    </div>
    {{$this->table}}
</div>
