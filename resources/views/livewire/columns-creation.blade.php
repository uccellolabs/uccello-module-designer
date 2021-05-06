<div @if ($step < 3)class="hidden"@endif>
    <x-md-vertical-step-card title="{{ trans('module-designer::ui.block.create_columns.title') }}" step="3" closed="{{ $step !== 3 }}">
        @if ($step === 3)
        <div class="col-span-6 p-12 border-b border-gray-200 border-solid">

            <div class="flex">
                {{-- Add columns --}}
                <div class="flex flex-grow mb-2 text-sm">{{ trans('module-designer::ui.block.create_columns.add_columns') }}</div>

                {{-- System fields --}}
                <div class="relative" x-data="{open:false, deleting:false}" x-on:click.away="open=false; deleting=false">
                    <a class="text-sm cursor-pointer" x-on:click="open=true">
                        {{ trans('module-designer::ui.block.create_columns.display_system_field') }}
                    </a>

                    <div class="absolute p-2 bg-white rounded-md shadow-md -right-2 top-8" x-show="open">
                        <div class="flex flex-col">
                            <a class="flex flex-row px-4 py-2 align-middle" wire:click="addSystemField('{{ trans('module-designer::ui.block.create_columns.system_field.created_at') }}')">
                                {{ trans('module-designer::ui.block.create_columns.system_field.created_at') }}
                            </a>
                            <a class="flex flex-row px-4 py-2 align-middle" wire:click="addSystemField('{{ trans('module-designer::ui.block.create_columns.system_field.created_by') }}')">
                                {{ trans('module-designer::ui.block.create_columns.system_field.created_by') }}
                            </a>
                            <a class="flex flex-row px-4 py-2 align-middle" wire:click="addSystemField('{{ trans('module-designer::ui.block.create_columns.system_field.updated_at') }}')">
                                {{ trans('module-designer::ui.block.create_columns.system_field.updated_at') }}
                            </a>
                            <a class="flex flex-row px-4 py-2 align-middle" wire:click="addSystemField('{{ trans('module-designer::ui.block.create_columns.system_field.workspace') }}')">
                                {{ trans('module-designer::ui.block.create_columns.system_field.workspace') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columns list --}}
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <ul class="grid items-center grid-cols-4 gap-2 outline-none" wire:sortable="updateColumnsOrder">
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

            {{-- Preview --}}
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
        @endif

        <x-slot name="after">
            {{-- Field config --}}
            <div class="justify-self-center">
                <x-md-vertical-step-card-title title="{{ trans('module-designer::ui.block.config_columns.title') }}" closed="{{ $step !== 4 }}" step="4"></x-vertical-step-card-title>
            </div>

            @if ($step === 4)
            <div class="p-6 border-b border-gray-200 border-solid">
                @foreach($fields->sortBy('sequence') as $index => $field)
                    <x-md-field-config :field="$field" :index="$index"></x-md-field-config>
                @endforeach
            </div>
            @endif

            {{-- Record Label --}}
            <div class="justify-self-center">
                <x-md-vertical-step-card-title title="{{ trans('module-designer::ui.block.define_label.title') }}" closed="{{ $step !== 5 }}" step="5"></x-vertical-step-card-title>
            </div>

            @if ($step === 5)
            <div class="p-6 border-b border-gray-200 border-solid">
                <span class="mb-2 text-sm">{{ trans('module-designer::ui.block.define_label.record_label') }}</span>
                <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                    <input type="text" wire:model="structure.recordLabel" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                </div>
            </div>
            @endif
        </x-slot>

        {{-- Separator --}}
        <div class="absolute z-10 grid w-2/3 -bottom-14">
            <img src="{{ ucasset('img/step-link.png', 'uccello/module-designer') }}" width="20" class="justify-self-center">
            <div class="absolute z-20 flex w-12 h-12 cursor-pointer justify-self-center primary rounded-xl -bottom-7 @if($step > 5)hidden @endif" wire:click="incrementStep">
                <x-mdicon-column-detail class="w-6 mx-auto my-auto"/>
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
