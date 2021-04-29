<div @if ($step < 2)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_columns.title') }}" step="2" closed="{{ $step > 2 }}">
        <div class="col-span-6 p-12 @if($step > 2)hidden @endif">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_columns.add_columns') }}</div>
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <ul class="grid grid-cols-4 gap-2 outline-none" wire:sortable="updateColumnsOrder">
                    @foreach($fields->sortBy('filterSequence') as $index => $field)
                    @php($field = (object) $field)
                    <li class="outline-none" wire:sortable.item="{{ $field->name }}" wire:key="field-{{ $field->name }}">
                        <x-md-column-tag :field="$field" :index="$index"></x-md-column-tag>
                    </li>
                    @endforeach

                    <li class="col-span-1">
                        <input type="text" wire:model="newColumn" placeholder="{{ trans('module-designer::ui.block.create_columns.column_name') }}" class="w-full px-2 bg-transparent browser-default focus:outline-none" autocomplete="false" wire:keydown.enter="createField()">
                    </li>
                </ul>
            </div>

            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.create_columns.list_preview') }}</div>
            <div class="p-4 border border-gray-200 border-solid rounded-lg shadow-md">
                <div class="flex">
                    <div class="pt-3">
                        <i class="text-base material-icons">search</i>
                    </div>
                    <div class="flex flex-row flex-grow max-w-2xl overflow-y-auto">
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
</div>
