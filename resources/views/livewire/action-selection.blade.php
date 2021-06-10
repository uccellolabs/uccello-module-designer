<div>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.choose_action.title') }}" step="0" closed="{{ $step > 0 }}">
        <div class="p-12 @if($step > 0)hidden @endif">
            {{ trans('module-designer::ui.block.choose_action.choose_action') }}
            <div class="grid grid-cols-3 gap-4 mt-3">
                {{-- Create --}}
                {{-- <a class="w-full px-4 py-2 text-center text-white rounded-lg cursor-pointer justify-self-center @if(!$action || $action === 'create')bg-green-500 @else bg-green-300 @endif" wire:click="changeAction('create')">{{ trans('module-designer::ui.block.choose_action.create_module') }}</a> --}}
                <a class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('create')">
                    @if($action === 'create')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-plus  @if(!$action)text-blue-400 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold text-center">{{ trans('module-designer::ui.block.choose_action.create_module') }}</span>
                </a>

                {{-- Edit --}}
                {{-- <a class="w-full px-4 py-2 text-center text-white rounded-lg cursor-pointer justify-self-center @if(!$action || $action === 'edit')bg-yellow-500 @else bg-yellow-300 @endif" wire:click="changeAction('edit')">{{ trans('module-designer::ui.block.choose_action.edit_module') }}</a> --}}
                <a class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('edit')">
                    @if($action === 'edit')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-pen @if(!$action)text-yellow-400 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold">{{ trans('module-designer::ui.block.choose_action.edit_module') }}</span>
                </a>

                {{-- Delete --}}
                <a class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('delete')">
                    @if($action === 'delete')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-trash  @if(!$action)text-red-500 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold">{{ trans('module-designer::ui.block.choose_action.delete_module') }}</span>
                </a>
            </div>

            {{-- Edit module --}}
            @if($action === 'edit')
            <div class="mt-6">
                {{ trans('module-designer::ui.block.choose_action.select_module') }}
                <div class="grid grid-cols-4 mt-3 gap-2 p-6 border border-gray-200 border-solid rounded-lg bg-gray-100 @if($step > 0)hidden @endif">
                    @forelse($crudModules->sortBy('label') as $crudModule)
                        <a class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="selectModuleToEdit({{ $crudModule['id'] }})">
                            @if($editedModuleId == $crudModule['id'])<i class="mr-2 text-green-500 fas fa-check"></i>@endif
                            <span class="pr-1 text-sm font-semibold">
                                @if(!empty($crudModule['label']))
                                    {{ $crudModule['label'] }}
                                @else
                                    {{ trans('module-designer::ui.block.choose_action.name_not_defined') }}
                                @endif
                            </span>
                        </a>
                    @empty
                        <div class="flex col-span-4 text-center text-gray-400 justify-self-center">{!! trans('module-designer::ui.block.choose_action.error.no_modules') !!}</div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- Delete module --}}
        @if($action === 'delete')
            <div class="mt-6">
                {{ trans('module-designer::ui.block.choose_action.select_module_to_delete') }}
                <div class="grid grid-cols-4 mt-3 gap-2 p-6 bg-gray-100 border border-gray-200 border-solid rounded-lg @if($step > 0)hidden @endif">
                    @forelse($crudModules->sortBy('label') as $crudModule)
                        <a class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="selectModuleToDelete({{ $crudModule['id'] }})">
                            @if($deletedModuleId == $crudModule['id'])<i class="mr-2 text-green-500 fas fa-check"></i>@endif
                            <span class="pr-1 text-sm font-semibold">
                                @if(!empty($crudModule['label']))
                                    {{ $crudModule['label'] }}
                                @else
                                    {{ trans('module-designer::ui.block.choose_action.name_not_defined') }}
                                @endif
                            </span>
                        </a>
                    @empty
                        <div class="flex col-span-4 text-center text-gray-400 justify-self-center">{!! trans('module-designer::ui.block.choose_action.error.no_modules') !!}</div>
                    @endforelse
                </div>
            </div>
        @endif
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14" x-data="{deleting: @entangle('isDeleting'), canDelete: @entangle('canDelete')}">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            @if ($action === 'delete')
                <div class="absolute z-20 grid -bottom-7 justify-self-center font-semibold cursor-pointer @if($step > 0)hidden @endif">
                    {{-- Delete --}}
                    <button x-show="!deleting" x-on:click="deleting=true"
                        x-bind:disabled="!canDelete"
                        class="grid @if($canDelete)bg-white hover:bg-gray-100 text-red-500 border border-red-500  @else bg-gray-300 text-white  @endif w-48 h-12 font-semibold justify-self-center justify-items-center items-center rounded-xl @if($step > 0)hidden @endif">
                        {{ trans('module-designer::ui.button.delete') }}
                    </button>

                    {{-- Delete confirm --}}
                    <a x-show="deleting" class="grid items-center w-48 h-12 font-semibold text-white bg-red-500 border border-red-500 justify-self-center justify-items-center rounded-xl" wire:click="initModuleDesign">
                        {{ trans('module-designer::ui.button.delete_confirm') }}
                    </a>
                </div>
            @else
                <a class="absolute z-20 grid w-48 h-12 justify-items-center text-white font-semibold items-center cursor-pointer justify-self-center @if ($action === 'create' || !empty($editedModuleId))bg-primary-500 @else bg-gray-300 @endif rounded-xl -bottom-7 @if($step > 0)hidden @endif" wire:click="initModuleDesign">
                    {{ trans('module-designer::ui.button.continue') }}
                </a>
            @endif
        </div>
    </x-md-vertical-step-card>
</div>
