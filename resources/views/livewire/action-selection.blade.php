<div>
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

                {{-- Delete --}}
                <div class="flex flex-row items-center px-2 py-2 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="changeAction('delete')">
                    @if($action === 'delete')
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @else
                        <i class="ml-2 fas fa-trash  @if(!$action)text-red-500 @else text-gray-300 @endif"></i>
                    @endif
                    <span class="pr-1 ml-2 text-base font-semibold">{{ trans('module-designer::ui.block.choose_action.delete_module') }}</span>
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
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Delete module --}}
        @if($action === 'delete')
            <div class="mt-6">
                {{ trans('module-designer::ui.block.choose_action.select_module_to_delete') }}
                <div class="grid grid-cols-4 mt-3 gap-2 p-6 bg-gray-100 border border-gray-200 border-solid rounded-lg @if($step > 0)hidden @endif">
                    @foreach($crudModules->sortBy('label') as $crudModule)
                        <div class="flex flex-row items-center px-2 py-1 bg-white border border-gray-200 border-solid rounded-lg shadow cursor-pointer hover:bg-gray-50" wire:click="selectModuleToDelete({{ $crudModule['id'] }})">
                            @if($deletedModuleId == $crudModule['id'])<i class="mr-2 text-green-500 fas fa-check"></i>@endif
                            <span class="pr-1 text-sm font-semibold">
                                @if(!empty($crudModule['label']))
                                    {{ $crudModule['label'] }}
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
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center rounded-xl -bottom-6 @if($canDesignModule) primary @else bg-gray-300 @endif @if($step > 0)hidden @endif" wire:click="initModuleDesign()">
                <x-mdicon-column-create class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
