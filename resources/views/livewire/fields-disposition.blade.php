<div @if ($step < 6)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.config_detail.title') }}" step="6" closed="{{ $step !== 6 }}">
        @if ($step === 6)
        <div class="p-6">
            {{-- Block --}}
            <div wire:sortable="updateBlockOrder" wire:sortable-group="updateBlockFieldOrder">
                @foreach ($blocks->sortBy('sequence') as $index => $block)
                    {{-- <x-md-block :block="$block" :fields="$fields" :index="$index" :areAvailableFields="$areAvailableFields"></x-md-block> --}}
                    <x-md-block :block="$block" :index="$index"></x-md-block>
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
        @endif

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 grid w-40 h-12 justify-items-center text-white font-semibold items-center cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 6)hidden @endif" wire:click="incrementStep">
                {{ trans('module-designer::ui.button.continue') }}
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
