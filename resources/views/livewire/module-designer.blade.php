<div>
    @if ($step >= 0)
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.choose_action.title') }}" step="0" closed="{{ $step > 0 }}">
        <div class="p-12 @if($step > 0)hidden @endif">
            {{ trans('module-designer::ui.block.choose_action.choose_action') }}
            <div class="grid grid-cols-3 gap-4 mt-3">
                {{-- Create --}}
                {{-- <a class="w-full px-4 py-2 text-center text-white rounded-lg cursor-pointer justify-self-center @if(!$action || $action === 'create')bg-green-500 @else bg-green-300 @endif" wire:click="changeAction('create')">{{ trans('module-designer::ui.block.choose_action.create_module') }}</a> --}}
                <div class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('create')">
                    @if($action === 'create')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-plus  @if(!$action)text-blue-400 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold text-center">{{ trans('module-designer::ui.block.choose_action.create_module') }}</span>
                </div>

                {{-- Edit --}}
                {{-- <a class="w-full px-4 py-2 text-center text-white rounded-lg cursor-pointer justify-self-center @if(!$action || $action === 'edit')bg-yellow-500 @else bg-yellow-300 @endif" wire:click="changeAction('edit')">{{ trans('module-designer::ui.block.choose_action.edit_module') }}</a> --}}
                <div class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('edit')">
                    @if($action === 'edit')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-pen @if(!$action)text-yellow-400 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold">{{ trans('module-designer::ui.block.choose_action.edit_module') }}</span>
                </div>

                {{-- Continue --}}
                {{-- <a class="w-full px-4 py-2 text-center text-white rounded-lg cursor-pointer justify-self-center @if(!$action || $action === 'continue')primary @else bg-blue-300 @endif" wire:click="changeAction('continue')">{{ trans('module-designer::ui.block.choose_action.continue_creation') }}</a> --}}
                <div class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('continue')">
                    @if($action === 'continue')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-redo  @if(!$action)text-red-500 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold">{{ trans('module-designer::ui.block.choose_action.continue_creation') }}</span>
                </div>
            </div>

            {{-- Edit module --}}
            @if($action === 'edit')
                <div class="mt-6">
                    {{ trans('module-designer::ui.block.choose_action.select_module') }}
                    <div class="grid grid-cols-4 mt-3 gap-2 p-6 border border-gray-200 border-solid rounded-lg bg-gray-100 @if($step > 0)hidden @endif">
                        @foreach($crudModules->sortBy('label') as $crudModule)
                            <div class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="selectModuleToEdit({{ $crudModule['id'] }})">
                                @if($editedModuleId == $crudModule['id'])<i class="mr-2 text-green-500 fas fa-check"></i>@endif
                                <span class="pr-1 text-sm font-semibold">
                                    @if(!empty($crudModule['label']))
                                        {{ $crudModule['label'] }}
                                    @else
                                        {{ trans('module-designer::ui.block.choose_action.name_not_defined') }}
                                    @endif</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Continue creation --}}
            @if($action === 'continue')
                <div class="mt-6">
                    {{ trans('module-designer::ui.block.choose_action.select_designed_module') }}
                    <div class="grid grid-cols-4 mt-3 gap-2 p-6 bg-gray-100 border border-gray-200 border-solid rounded-lg @if($step > 0)hidden @endif">
                        @foreach($designedModules->sortBy('data.label') as $designedModule)
                            <div class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="selectDesignedModuleToEdit({{ $designedModule->id }})">
                                @if($editedDesignedModuleId == $designedModule->id)<i class="mr-2 text-green-500 fas fa-check"></i>@endif
                                <span class="pr-1 text-sm font-semibold">
                                    @if(!empty($designedModule->data->label))
                                        {{ $designedModule->data->label }}
                                    @else
                                        {{ trans('module-designer::ui.block.choose_action.name_not_defined') }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center rounded-xl -bottom-6 @if($canDesignModule) primary @else bg-gray-300 @endif @if($step > 0)hidden @endif" wire:click="changeStep(1)">
                <x-mdicon-column-create class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
    @endif

    @if ($step === 1)
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_module.title') }}" step="1" closed="{{ $step > 1 }}">
        <div class="flex p-12 @if($step > 1)hidden @endif">
            {{-- Left column --}}
            <div class="w-2/6 mr-4">
                <div class="mb-2 mr-10 text-sm text-right">{{ trans('module-designer::ui.block.create_module.icon') }}</div>
                <div class="float-right">
                    @livewire('icon-selector', ['target' => 'module', 'icon' => $structure['icon']])
                </div>
            </div>
            {{-- Right column --}}
            <div class="w-3/6">
                <div class="">
                    <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.module_name') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="moduleLabel" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                    <div class="mt-8 mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.slug') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="structure.name" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 1)hidden @endif" wire:click="incrementStep">
                <x-mdicon-column-create class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
    @endif

    @if ($step > 1)
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_columns.title') }}" step="2" closed="{{ $step > 2 }}">
        <div class="col-span-6 p-12 @if($step > 2)hidden @endif">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_columns.add_columns') }}</div>
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <ul class="grid grid-cols-3 gap-2 outline-none" wire:sortable="updateColumnsOrder">
                    @foreach($fields->sortBy('filterSequence') as $index => $field)
                    @php($field = (object) $field)
                    <li class="outline-none" wire:sortable.item="{{ $field->name }}" wire:key="field-{{ $field->name }}">
                        <x-md-column-tag :field="$field" :index="$index"></x-md-column-tag>
                    </li>
                    @endforeach

                    <div>
                        <input type="text" wire:model="column" placeholder="{{ trans('module-designer::ui.block.create_columns.column_name') }}" class="w-full px-2 bg-transparent browser-default focus:outline-none" autocomplete="false" wire:keydown.enter="createField()">
                    </div>
                </ul>
            </div>

            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_columns.list_preview') }}</div>
            <div class="p-4 border border-gray-200 border-solid rounded-lg shadow-md">
                <div class="flex">
                    <div class="pt-3">
                        <i class="text-base material-icons">search</i>
                    </div>
                    <div class="flex flex-row flex-grow overflow-y-auto">
                        @forelse($fields->sortBy('filterSequence') as $field)
                            @continue(!((object) $field)->isDisplayedInListView)
                            <x-md-column :field="$field"></x-md-column>
                        @empty
                            <x-md-column></x-md-column>
                        @endforelse
                    </div>

                    <div>
                        <div class="flex items-center justify-center w-6 h-6 rounded-full primary">
                            <i class="text-base text-white material-icons">view_column</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($step === 3)
        <x-slot name="after">
            <div class="@if ($step < 3)border-t @endif border-gray-200 border-solid justify-self-center">
                <x-md-vertical-step-card-title title="{{ trans('module-designer::ui.block.config_columns.title') }}" :closed="false" step="3"></x-vertical-step-card-title>
            </div>
            <div class="p-6">
                @foreach($fields->sortBy('sequence') as $index => $field)
                    <x-md-field-config :field="$field" :index="$index"></x-md-field-config>
                @endforeach
            </div>
        </x-slot>
        @endif

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 3)hidden @endif" wire:click="incrementStep">
                <x-mdicon-column-detail class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
    @endif

    @if ($step > 3)
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.config_detail.title') }}" step="4">
        <div class="p-6">
            {{-- Block --}}
            <div wire:sortable="updateBlockOrder" wire:sortable-group="updateBlockFieldOrder">
                @foreach ($this->blocks->sortBy('sequence') as $index => $block)
                    <x-md-block :block="$block" :fields="$fields" :index="$index" :areAvailableFields="$areAvailableFields"></x-md-block>
                @endforeach
            </div>

            {{-- Add block --}}
            <div class="grid p-4 mt-6 text-center border-2 border-gray-300 border-dashed rounded-lg">
                <a class="flex items-center px-2 py-1 text-sm text-white rounded-lg cursor-pointer justify-self-center primary" wire:click="createBlock()">
                    {{ trans('module-designer::ui.block.config_detail.add_block') }}
                    <i class="ml-1 text-sm material-icons">add</i>
                </a>
            </div>
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 4)hidden @endif" wire:click="incrementStep">
                <x-mdicon-column-create class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
    @endif
</div>
