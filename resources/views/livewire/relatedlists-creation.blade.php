<div @if ($step < 7)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_relatedlists.title') }}" step="7">
        <div class="p-6">
            A intégrer
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 grid w-40 h-12 justify-items-center text-white font-semibold items-center cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 7)hidden @endif" wire:click="incrementStep">
                {{ trans('module-designer::ui.button.continue') }}
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
