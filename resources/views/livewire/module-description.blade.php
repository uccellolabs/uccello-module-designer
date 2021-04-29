<div @if ($step < 1)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_module.title') }}" step="1" closed="{{ $step > 0 }}">
        <div class="flex p-12 @if($step > 1)hidden @endif">
            {{-- Left column --}}
            <div class="w-2/6 mr-4">
                <div class="mb-2 mr-10 text-sm text-right">{{ trans('module-designer::ui.block.create_module.icon') }}</div>
                <div class="float-right">
                    @livewire('icon-selector', ['target' => 'module', 'icon' => $structure['icon'] ?? null])
                </div>
            </div>
            {{-- Right column --}}
            <div class="w-3/6">
                <div class="">
                    <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_module.module_name') }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="structure.label" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
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
</div>
