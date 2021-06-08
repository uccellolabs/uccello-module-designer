<div x-data="{open: @if($field->isEditable)true @else false @endif, edit: false, advanced: false}">
    <div class="flex items-center">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $field->color }}"></div>
        {{-- Label --}}
        <span class="mr-1 text-sm font-semibold" x-show="!edit">{{ $field->label }}</span>
        <input type="text" x-show="edit" x-ref="field_{{ $field->name }}" class="text-sm font-semibold outline-none browser-default" wire:model="fields.{{ $index }}.label" @click.away="edit=false">
        {{-- Edit icon --}}
        <i class="text-base cursor-pointer material-icons" x-show="!edit" x-on:click="edit=true; $refs['field_{{ $field->name }}'].select();">create</i>
        {{-- Line --}}
        <div class="flex-grow h-1 mx-3 border-b border-gray-400 border-solid"></div>
        {{--Less/More icon --}}
        <div class="flex items-center justify-center w-4 h-4 text-white rounded-full cursor-pointer primary" x-on:click="open=!open">
            <i class="text-xs material-icons" x-show="open">expand_less</i>
            <i class="text-xs material-icons" x-show="!open">expand_more</i>
        </div>
    </div>


    <div class="flex flex-row justify-between mt-6" x-show="open">
        {{-- UIType --}}
        <div class="flex flex-col w-4/12">
            <div class="mb-1">{{ trans('module-designer::ui.block.config_columns.uitype') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-md">
                <select class="w-full h-10 px-3 bg-transparent w-52 browser-default" wire:model="fields.{{ $index }}.uitype" wire:change="changeUitype('{{ $field->name }}')" @if(!$field->isFullyEditable)disabled="false"@endif>
                    @foreach ($uitypes as $uitype)
                    <option value="{{ $uitype->name }}" @if((!$field->isFullyEditable) && $field->uitype === $uitype->name)selected="selected"@endif>{{ $uitype->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex flex-col w-4/12">
            <div class="mb-1">{{ trans('module-designer::ui.block.config_columns.name') }}</div>
            <input type="text" class="w-full p-2 bg-gray-100 border border-gray-200 rounded-md bg-transparent browser-default @if(!$field->isFullyEditable)text-gray-400 @endif"" value="{{ $field->name }}" @if(!$field->isFullyEditable)disabled="disabled"@endif>
        </div>
        <div class="flex flex-col w-2/12">
            <div class="mb-3">{{ trans('module-designer::ui.block.config_columns.required') }}</div>

            <div class="flex flex-row mb-3 cursor-pointer" wire:click="toggleIsRequired('{{ $field->name }}')">
                @php($isRequired = $field->isRequired ?? true)
                <div class="flex items-center">
                    <div class="relative flex items-center w-10 h-5 transition duration-200 ease-linear border-2 rounded-full @if($isRequired) border-primary-900 @else border-gray-400 @endif">
                        <div class="absolute w-3 h-3 transition duration-100 ease-linear transform rounded-full cursor-pointer left-1 @if($isRequired) translate-x-4 bg-primary-900 @else translate-x-0 bg-gray-400 @endif"></div>
                    </div>
                </div>
                <div class="ml-2">{{ trans('module-designer::ui.block.config_module.yes') }}</div>
            </div>
        </div>
    </div>

    @if ($field->options)
    <div class="p-5 mt-4 rounded-lg bg-primary-500 bg-opacity-10">
        <div class="grid grid-cols-3 mb-3">
            @foreach ($field->options as $option)
                @php($option = (object) $option)

                {{-- Input --}}
                @if(in_array($option->type, ['text', 'email', 'number', 'password']))
                    <div class="flex flex-col ml-4">
                        <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                        <div class="bg-gray-100 border rounded-md border-primary-500">
                            <input type="{{ $option->type }}"
                                class="w-full px-3 py-2 bg-transparent browser-default"
                                wire:model="fields.{{ $index }}.data.{{ $option->key }}">
                        </div>
                    </div>
                {{-- Boolean --}}
                @elseif($option->type === 'boolean')
                    <div class="flex flex-col ml-4">
                        <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                        <div class="h-10 pt-1 switch">
                            <label>
                            <input type="checkbox" wire:model="fields.{{ $index }}.data.{{ $option->key }}">
                            <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                            {{ trans('module-designer::ui.block.config_columns.yes') }}
                            </label>
                        </div>
                    </div>
                {{-- Select --}}
                @elseif($option->type === 'select')
                    <div class="flex flex-col ml-4">
                        <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                        <div class="bg-gray-100 border rounded-md border-primary-500">
                            <select class="w-full h-10 px-3 bg-transparent rounded-md browser-default"
                                wire:model="fields.{{ $index }}.data.{{ $option->key }}"
                                @if ($option->altersDynamicFields ?? false)wire:change="reloadFieldOptions({{ $index }})"@endif>
                                @foreach ($option->choices as $choice)
                                    @php($choice = (object) $choice)
                                    <option value="{{ $choice->value }}">{{ $choice->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                {{-- Array --}}
                @elseif($option->type === 'array')
                    @php ($fieldData = (array) $field->data)
                    @if (!empty($fieldData[$option->key]))
                        @foreach ($fieldData[$option->key] as $rowIndex => $row)
                            <div class="flex flex-col ml-4">
                                <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.array_value') }}</div>
                                <div class="bg-gray-100 border rounded-md rounded-lg border-primary-500">
                                    <input type="text"
                                        class="w-full px-3 py-2 bg-transparent browser-default"
                                        wire:model="fields.{{ $index }}.data.{{ $option->key }}.{{ $rowIndex }}.value">
                                </div>
                            </div>
                            <div class="flex flex-col ml-4">
                                <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.array_label') }}</div>
                                <div class="bg-gray-100 border rounded-md rounded-lg border-primary-500">
                                    <input type="text"
                                        class="w-full px-3 py-2 bg-transparent browser-default"
                                        wire:model="fields.{{ $index }}.data.{{ $option->key }}.{{ $rowIndex }}.label">
                                </div>
                            </div>
                            <div class="flex items-center ml-4">
                                @if (count($fieldData[$option->key]) > 1)
                                <a class="px-2 py-1 text-center text-white cursor-pointer red rounded-xl" wire:click="deleteRowFromFieldOptionArray('{{ $field->name }}', '{{ $option->key }}', {{ $rowIndex }})">
                                    <i class="text-base material-icons">delete</i>
                                </a>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <a class="w-12 px-2 py-1 mt-4 ml-4 text-center text-white cursor-pointer primary rounded-xl" wire:click="addRowToFieldOptionArray('{{ $field->name }}', '{{ $option->key }}')">
                        <i class="text-base material-icons">add</i>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-3 mb-6 text-sm" x-show="open" x-on:click="advanced=true">
        <div class="text-right underline cursor-pointer">
            {{ trans('module-designer::ui.block.config_columns.advanced_params') }}
        </div>

        <div x-show="advanced">
            <select class="browser-default" wire:model="fields.{{ $index }}.displaytype">
                @foreach ($displaytypes as $displaytype)
                <option value="{{ $displaytype->name }}">{{ $displaytype->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
