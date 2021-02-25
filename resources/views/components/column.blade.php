<div class="flex-grow px-2 ">
    <div class="flex flex-row items-center pb-2 mb-3 border-b border-gray-400 border-solid">
        <div class="rounded-full h-3 w-3 flex items-center justify-center mr-2 {{ $field->color ?? 'bg-red-200' }}">&nbsp;</div>
        <span class="flex-grow pr-1 text-sm font-semibold whitespace-nowrap">{{ $field->label ?? 'Colonne 1' }}</span>

        @if($field->sortOrder === 'asc')
            <i class="text-sm text-gray-400 fas fa-sort-amount-up"></i>
        @elseif($field->sortOrder === 'desc')
            <i class="text-sm text-gray-400 fas fa-sort-amount-down"></i>
        @endif
    </div>
    <div class="px-3 py-2 bg-gray-100">
        <div class="h-3 bg-gray-300 rounded-full"></div>
    </div>
    <div class="px-3 py-2 bg-white">
        <div class="h-3 bg-gray-300 rounded-full"></div>
    </div>
    <div class="px-3 py-2 bg-gray-100">
        <div class="h-3 bg-gray-300 rounded-full"></div>
    </div>
</div>
