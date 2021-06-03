<form @if ($step < 1)class="hidden"@endif wire:submit.prevent="saveModule">
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_module.title') }}" step="1" closed="{{ $step > 0 }}">
        <div class="flex p-12 @if($step > 1)hidden @endif">
            <div class="grid grid-cols-4">

            </div>
            {{-- Left column --}}
            <div class="w-2/6 mr-4">
                <div class="mb-2 mr-10 text-sm text-right">{{ trans('module-designer::ui.block.create_module.icon') }}</div>
                <div class="float-right">
                    {{-- @livewire('icon-selector', ['target' => 'module', 'icon' => $structure['icon'] ?? null]) --}}
                </div>
            </div>
            {{-- Right column --}}
            <div class="w-3/6">
                <div class="">
                    {{-- Plural --}}
                    <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.module_name_plural') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="label" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                    @error('label') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                    {{-- Singular --}}
                    <div class="mt-2 mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.module_name_singular') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="label_singular" class="w-full px-3 py-2 bg-transparent outline-none browser-default" autocomplete="off">
                    </div>
                    @error('label_singular') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                    {{-- Category --}}
                    {{-- <div class="mt-2 mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.category') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="category" class="w-full px-3 py-2 bg-transparent outline-none browser-default" autocomplete="off">
                    </div>
                    @error('category') <span class="text-sm text-red-500">{{ $message }}</span> @enderror --}}

                    {{-- Name --}}
                    <div class="mt-2 mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.name') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text"
                            wire:model="name"
                            {{-- wire:keyup.debounce.1s="checkModuleNameAvailability()" --}}
                            class="w-full px-3 py-2 bg-transparent outline-none browser-default @if(!$isModuleNameAvailable) border-b-2 border-red-500 border-solid @endif @if(optional($structure)['isEditing'])text-gray-400 @endif"
                            @if(optional($structure)['isEditing'])disabled="disabled"@endif
                            autocomplete="off">
                    </div>
                    @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                    @if(!$isModuleNameAvailable)
                    <div class="mt-1 text-sm text-red-500">
                        {{ trans('module-designer::ui.block.config_module.error.name_alreay_used') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <x-slot name="after">
            <div class="@if ($step < 2)border-t @endif border-gray-200 border-solid justify-self-center">
                <x-md-vertical-step-card-title title="{{ trans('module-designer::ui.block.config_module.title') }}" closed="{{ $step !== 2 }}" step="2"></x-vertical-step-card-title>
            </div>

            @if ($step === 2)
            <div class="p-6">
                {{-- For administration --}}
                {{ trans('module-designer::ui.block.config_module.for_admin') }}
                <div class="h-10 pt-1 switch">
                    <label>
                      <input type="checkbox" @if (isset($structure['admin']) && $structure['admin'] === true)checked="true"@endif wire:model="structure.admin">
                      <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                      {{ trans('module-designer::ui.block.config_module.yes') }}
                    </label>
                </div>

                {{-- Public / Private --}}
                <div class="cursor-pointer" wire:click="changeModuleVisibility('public')">
                    @if (!isset($structure['private']) || $structure['private'] === false)
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @endif

                    <span>{{ trans('module-designer::ui.block.config_module.public') }}</span>
                    <p>
                        {{ trans('module-designer::ui.block.config_module.public_description') }}
                    </p>
                </div>

                <div class="mt-4 cursor-pointer" wire:click="changeModuleVisibility('private')">
                    @if (isset($structure['private']) && $structure['private'] === true)
                        <i class="ml-2 text-green-500 fas fa-check"></i>
                    @endif

                    <span>{{ trans('module-designer::ui.block.config_module.private') }}</span>
                    <p>
                        {!! trans('module-designer::ui.block.config_module.private_description') !!}
                    </p>
                </div>
            </div>
            @endif
        </x-slot>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <button type="submit" class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 2)hidden @endif">
                <x-mdicon-column-create class="w-6 mx-auto my-auto"/>
            </button>
        </div>
    </x-md-vertical-step-card>
</form>
