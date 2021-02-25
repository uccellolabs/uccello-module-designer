<div x-data="{open: true, edit: false}">
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
    <div class="flex items-center my-3" x-show="open">
        {{-- Icon --}}
        <div class="flex flex-col">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.icon') }}</div>
            <x-md-icon-selector></x-md-icon-selector>
        </div>
        {{-- Uitype --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.uitype') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <select class="h-10 px-3 bg-transparent w-52 browser-default" wire:model="fields.{{ $index }}.uitype" wire:change="changeUitype('{{ $field->name }}')">
                    @foreach ($uitypes as $uitype)
                    <option value="{{ $uitype->name }}">{{ $uitype->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- Name --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.name') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" value="{{ $field->name }}">
            </div>
        </div>
        {{-- Mandatory --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.required') }}</div>
            <div class="h-10 pt-1 switch">
                <label>
                  <input type="checkbox" @if ($field->isRequired)checked="true"@endif wire:model="fields.{{ $index }}.isRequired">
                  <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                  {{ trans('module-designer::ui.block.config_columns.yes') }}
                </label>
              </div>
        </div>
    </div>
    <div class="grid grid-cols-3">
        @foreach ($field->options as $option)
            @php($option = (object) $option)

            {{-- Input --}}
            @if(in_array($option->type, ['text', 'email', 'number', 'password']))
                <div class="flex flex-col ml-4">
                    <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
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
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <select class="w-full h-10 px-3 bg-transparent browser-default"
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
                @foreach ($fieldData[$option->key] as $rowIndex => $row)
                    <div class="flex flex-col ml-4">
                        <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.array_value') }}</div>
                        <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                            <input type="text"
                                class="w-full px-3 py-2 bg-transparent browser-default"
                                wire:model="fields.{{ $index }}.data.{{ $option->key }}.{{ $rowIndex }}.value">
                        </div>
                    </div>
                    <div class="flex flex-col ml-4">
                        <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.array_label') }}</div>
                        <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
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

                <a class="w-12 px-2 py-1 mt-4 ml-4 text-center text-white cursor-pointer primary rounded-xl" wire:click="addRowToFieldOptionArray('{{ $field->name }}', '{{ $option->key }}')">
                    <i class="text-base material-icons">add</i>
                </a>
            @endif
        @endforeach
    </div>
    <div class="mt-3 mb-6 text-sm text-right underline" x-show="open">
        {{ trans('module-designer::ui.block.config_columns.advanced_params') }}
    </div>
</div>
