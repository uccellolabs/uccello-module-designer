<form @if ($step < 1)class="hidden"@endif wire:submit.prevent="saveModule">
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_module.title') }}" step="1" closed="{{ $step > 0 }}">
        <div class="flex p-12 @if($step > 1)hidden @endif">
            <div class="grid grid-cols-4">

            </div>
            {{-- Left column --}}
            <div class="w-2/6 mr-4">
                <div class="mb-2 mr-10 text-sm text-right">{{ trans('module-designer::ui.block.create_module.icon') }}</div>
                <div class="float-right">
                    @livewire('icon-selector', ['target' => 'module'])
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

        @if ($step === 2)
        <x-slot name="after">
            <div class="@if ($step < 2)border-t @endif border-gray-200 border-solid justify-self-center">
                <x-md-vertical-step-card-title title="{{ trans('module-designer::ui.block.config_module.title') }}" closed="{{ $step !== 2 }}" step="2"></x-vertical-step-card-title>
            </div>

            @if ($step === 2)
            <div class="p-6">
                {{-- For administration --}}
                <div class="mb-2">
                    {{ trans('module-designer::ui.block.config_module.for_admin') }}
                </div>

                <div class="flex flex-row mb-3 cursor-pointer" wire:click="toggleIsForAdmin">
                    @php($isForAdmin = isset($structure['admin']) && $structure['admin'] === true)
                    <div class="flex items-center">
                        <div class="relative flex items-center w-10 h-5 transition duration-200 ease-linear border-2 rounded-full @if($isForAdmin) border-primary-900 @else border-gray-400 @endif">
                            <div class="absolute w-3 h-3 transition duration-100 ease-linear transform rounded-full cursor-pointer left-1 @if($isForAdmin) translate-x-4 bg-primary-900 @else translate-x-0 bg-gray-400 @endif"></div>
                        </div>
                    </div>
                    <div class="ml-2">{{ trans('module-designer::ui.block.config_module.yes') }}</div>
                </div>

                {{-- Public / Private --}}
                <div class="mt-6 mb-2">{{ trans('module-designer::ui.block.config_module.access_mode') }}</div>

                <div class="flex flex-col">
                    @php($isPublic = !isset($structure['private']) || $structure['private'] === false)
                    <div class="cursor-pointer" wire:click="changeModuleVisibility('public')">
                        <div class="flex flex-col p-3 border rounded-md @if($isPublic) border-primary-500 bg-blue-50 @endif">
                            <div class="flex flex-row items-center">
                                <input class="mr-3 border-primary-900 checked:bg-primary-900 browser-default" type="radio" name="choice" @if($isPublic)checked="checked"@endif style="position: unset; opacity: 1">
                                <div class="font-semibold">{{ trans('module-designer::ui.block.config_module.public') }}</div>
                            </div>
                            <div class="flex flex-row ml-6">
                                <div class="">
                                    {!! trans('module-designer::ui.block.config_module.public_description') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 cursor-pointer" wire:click="changeModuleVisibility('private')">
                        <div class="flex flex-col p-3 mt-2 border rounded-md @if(!$isPublic) border-primary-500 bg-blue-50 @endif">
                            <div class="flex flex-row items-center">
                                <input class="mr-3 border-primary-900 checked:bg-primary-900" type="radio" name="choice" @if(!$isPublic)checked="checked"@endif style="position: unset; opacity: 1">
                                <div class="font-semibold">{{ trans('module-designer::ui.block.config_module.private') }}</div>
                            </div>
                            <div class="flex flex-col ml-6">
                                <p>
                                    {!! trans('module-designer::ui.block.config_module.private_description') !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </x-slot>
        @endif

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 grid w-40 h-12 justify-items-center text-white font-semibold items-center cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 2)hidden @endif" wire:click="incrementStep">
                {{ trans('module-designer::ui.button.continue') }}
            </div>
        </div>
    </x-md-vertical-step-card>
</form>
