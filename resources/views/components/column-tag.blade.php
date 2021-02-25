<div class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow hover:bg-gray-50">
    <div class="flex items-center flex-grow cursor-move " wire:sortable.handle>
        <div class="rounded-full h-3 w-3 flex items-center justify-center mr-2 {{ $field->color }}">&nbsp;</div>
        <span class="pr-1 text-sm font-semibold">{{ $field->label }}</span>
    </div>
    <a class="pl-1 ml-1 text-sm text-gray-400 border-l border-gray-200 border-solid cursor-pointer hover:text-blue-400" wire:click="toggleFilterSortOrder('{{ $field->name }}')">
        @if($field->sortOrder === 'asc')
            <i class="fas fa-sort-amount-up"></i>
        @elseif($field->sortOrder === 'desc')
            <i class="fas fa-sort-amount-down"></i>
        @else
            <i class="text-xs fas fa-filter"></i>
        @endif
    </a>

    <a class="ml-1 text-sm text-gray-400 cursor-pointer hover:text-blue-400" wire:click="toggleIsDisplayedInListView('{{ $field->name }}')">
        @if($field->isDisplayedInListView)
            <i class="far fa-eye"></i>
        @else
            <i class="far fa-eye-slash"></i>
        @endif
    </a>

</div>
