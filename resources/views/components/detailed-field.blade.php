<div class="h-10 bg-gray-100 rounded-lg @if($field->isLarge)col-span-2 @else col-span-1 @endif">
    <div class="flex items-center h-full p-3">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $field->color }}"></div>

        {{-- Label --}}
        <span class="flex-grow text-sm font-semibold">{{ $field->label }}</span>

        {{-- Icons --}}
        {{-- <a class="text-gray-600 cursor-pointer" wire:model="fields.{{ $index }}.isDisplayedInListView"><i class="text-base material-icons">home</i></a> --}}
        <a class="text-gray-600 cursor-pointer" wire:click="removeFieldFromBlock('{{ $field->name }}')"><i class="text-base material-icons">close</i></a>
    </div>
</div>
