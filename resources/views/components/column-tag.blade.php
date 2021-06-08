<div class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow hover:bg-gray-50">
    <div class="flex items-center flex-grow cursor-move" wire:sortable.handle>
        <div class="rounded-full h-3 w-3 flex items-center justify-center mr-2 {{ $field->color }}">&nbsp;</div>
        <span class="pr-1 text-sm font-semibold">{{ $field->label }}</span>
    </div>

    <div class="relative" x-data="{open:false, deleting:false}" x-on:click.away="open=false; deleting=false">
        <a class="px-1 text-sm text-gray-400 cursor-pointer hover:text-blue-400" x-on:click="open=true">
            <i class="fas fa-ellipsis-v"></i>
        </a>

        <div class="absolute z-20 w-48 p-2 bg-white rounded-md shadow-md -right-2 top-8" x-show="open">
            <div class="flex flex-col text-sm">
                <a class="flex flex-row items-center px-4 py-2 cursor-pointer hover:bg-gray-100" wire:click="toggleFilterSortOrder('{{ $field->name }}')">
                    @if($field->sortOrder === 'asc')
                        <i class="fas fa-sort-amount-up"></i>
                    @elseif($field->sortOrder === 'desc')
                        <i class="fas fa-sort-amount-down"></i>
                    @else
                        <i class="text-xs fas fa-filter"></i>
                    @endif

                    <span class="ml-2">{{ trans('module-designer::ui.block.create_columns.sort') }}</span>
                </a>
                <a class="flex flex-row items-center px-4 py-2 cursor-pointer hover:bg-gray-100" wire:click="toggleIsDisplayedInListView('{{ $field->name }}')">
                    @if($field->isDisplayedInListView)
                        <i class="far fa-eye"></i>
                    @else
                        <i class="far fa-eye-slash"></i>
                    @endif

                    <span class="ml-2">{{ trans('module-designer::ui.block.create_columns.display_hide') }}</span>
                </a>

                @if(!$field->isSystemField)
                <a class="flex flex-row px-4 py-2 text-red-500 cursor-pointer hover:bg-gray-100" x-on:click="deleting=true" x-show="!deleting">
                    <span class="ml-2">{{ trans('module-designer::ui.block.create_columns.delete') }}</span>
                </a>

                <a class="flex flex-row px-4 py-2 text-white bg-red-500 cursor-pointer" x-show="deleting" wire:click="deleteField('{{ $field->name }}')">
                    <span class="ml-2">{{ trans('module-designer::ui.block.create_columns.delete_confirm') }}</span>
                </a>
                @endif
            </div>
        </div>
    </div>

</div>
