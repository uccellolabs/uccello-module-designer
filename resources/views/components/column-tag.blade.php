<div class="flex flex-row items-center px-2 bg-white border border-gray-200 border-solid rounded-lg shadow hover:bg-gray-50">
    <div class="flex items-center flex-grow cursor-move " wire:sortable.handle>
        <div class="rounded-full h-3 w-3 flex items-center justify-center mr-2 {{ $field->color }}">&nbsp;</div>
        <span class="pr-1 text-sm font-semibold">{{ $field->label }}</span>
    </div>
    <a class="pl-1 ml-1 text-gray-400 border-l border-gray-200 border-solid cursor-pointer hover:text-blue-400"><i class="text-base material-icons">filter_alt</i></a>
    <a class="pl-1 text-gray-400 cursor-pointer hover:text-blue-400" wire:model="fields.{{ $index }}.isDisplayedInListView"><i class="text-base material-icons">@if ($field->isDisplayedInListView)visibility @else visibility_off @endif</i></a>
</div>
