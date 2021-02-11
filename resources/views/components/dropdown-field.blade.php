<a class="flex flex-row items-center px-2 py-1 bg-white rounded-lg cursor-pointer hover:bg-gray-50" wire:click="addFieldToBlock('{{ $block->uuid }}', '{{ $field->name }}')">
    <div class="flex items-center justify-center w-3 h-3 mr-2 rounded-full {{ $field->color }}">&nbsp;</div>
    <span class="flex-grow pr-1 text-sm font-semibold">{{ $field->label }}</span>
</a>
