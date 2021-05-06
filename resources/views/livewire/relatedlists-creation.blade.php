<div @if ($step < 7)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_relatedlists.title') }}" step="7">
        <div class="p-6">
            A intégrer
        </div>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 7)hidden @endif" wire:click="incrementStep">
                <x-mdicon-column-detail class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
