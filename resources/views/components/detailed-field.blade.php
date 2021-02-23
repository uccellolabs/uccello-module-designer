<div class="h-10 bg-gray-100 rounded-lg @if($field->isLarge)col-span-2 @else col-span-1 @endif" wire:key="field-{{ $field->name }}" wire:sortable-group.item="{{ $field->name }}">
    <div class="flex items-center h-full p-3">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $field->color }}"></div>

        {{-- Label --}}
        <span class="flex-grow text-sm font-semibold">{{ $field->label }}</span>

        {{-- Icons --}}
        <a class="text-gray-600 cursor-pointer" wire:click="toggleLarge('{{ $field->name }}')">
            @if ($field->isLarge)
                <x-mdicon-small-field class="w-4 mr-2"/>
            @else
                <x-mdicon-large-field class="w-4 mr-2"/>
            @endif
        </a>

        <a class="text-gray-600 cursor-pointer" wire:click="removeFieldFromBlock('{{ $field->name }}')"><i class="text-base material-icons">close</i></a>
    </div>
</div>
