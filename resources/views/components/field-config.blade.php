<div x-data="{open: @if($field->isEditable)true @else false @endif, edit: false, advanced: false}">

    <div class="mb-8" x-data="{open:false}">
        <div class="flex flex-row items-center my-2">
            <div class="flex flex-row items-center justify-between w-2/12">
                <div class="">
                    <div class="p-1 bg-pink-400 rounded-full"></div>
                </div>
                <div class="">Entête 1</div>
                <div class="mr-2">
                    <img width=70% src="{{ ucasset('img/pen-icon_picto.svg') }}">
                </div>
            </div>
            <div class="w-9/12 border-t border-primary-900"></div>
            <div class="flex justify-end w-1/12">
                <div class="text-white rounded-full bg-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-between">
            <div class="flex flex-col w-4/12" x-data="{dropdown:false}">
                <div class="mb-1">Type de champ</div>
                <div class="relative flex flex-col p-2 bg-gray-100 border border-gray-200 rounded-md">
                    <div class="flex flex-row items-center justify-between cursor-pointer" x-on:click="dropdown=!dropdown">
                        <div class="flex flex-row items-center">
                            <div class="mr-1">
                                <img width=70% src="{{ ucasset('img/type-group_picto.svg') }}">
                            </div>
                            <div class="">Champ lié</div>
                        </div>
                        <div class=""><img width=70% src="{{ ucasset('img/triangle_picto.svg') }}"></div>
                    </div>
                    {{--  Dropdown --}}
                    <div class="absolute left-0 right-0 bg-gray-100 border-b border-l border-r border-gray-200 top-8 rounded-b-md" x-show="dropdown" @click.away="dropdown = false">
                        <div class="flex flex-row p-2">
                            <div class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <div class="">Texte simple</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-4/12">
                <div class="mb-1">Nom système</div>
                <input type="text" class="w-full p-2 bg-gray-100 border border-gray-200 rounded-md">
            </div>
            <div class="flex flex-col w-2/12">
                <div class="mb-3">Obligatoire</div>
                <div class="flex flex-row">
                    <div class="flex items-center" x-data="{toggle: '0'}">
                        <div class="relative flex items-center w-10 h-5 transition duration-200 ease-linear border-2 rounded-full" :class="[toggle === '1' ? 'border-primary-900' : 'border-gray-400']">
                        <label for="toggle"
                                class="absolute w-3 h-3 transition duration-100 ease-linear transform rounded-full cursor-pointer left-1"
                                :class="[toggle === '1' ? 'translate-x-4 bg-primary-900' : 'translate-x-0 bg-gray-400']"></label>
                        <input type="checkbox" id="toggle2" name="toggle"
                                class="w-full h-full appearance-none active:outline-none focus:outline-none"
                                x-on:click="toggle === '0' ? toggle = '1' : toggle = '0'"/>
                        </div>
                    </div>
                    <div class="ml-2">Oui</div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="rounded-lg bg-primary-500 bg-opacity-10">
                <div class="p-5">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col w-4/12">
                            <div class="mb-1">Module lié</div>
                            <div class="relative flex flex-row items-center">
                                <img width="13%" class="absolute pl-2" src="{{ ucasset('img/modal-group_picto.svg') }}">
                                <input type="text" class="w-full p-2 pl-8 bg-gray-100 border rounded-md border-primary-500">
                            </div>
                        </div>
                        <div class="flex flex-col w-4/12">
                            <div class="mb-1">Libellé du champ</div>
                            <input type="text" class="w-full p-2 pl-3 bg-gray-100 border rounded-md border-primary-500">
                        </div>
                        <div class="flex flex-col w-1/12">
                            <div class="mb-1">Afficher</div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end w-1/12">
                            <div class="">
                                <div class="p-2 text-white rounded bg-primary-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="p-2 text-center text-white rounded-md cursor-pointer bg-primary-900">
                            + Lier à un nouveau champ
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        {{-- <div class="flex flex-col">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.icon') }}</div>
            <x-md-icon-selector></x-md-icon-selector>
        </div> --}}
        {{-- Uitype --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.uitype') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <select class="h-10 px-3 bg-transparent w-52 browser-default" wire:model="fields.{{ $index }}.uitype" wire:change="changeUitype('{{ $field->name }}')" @if(!$field->isFullyEditable)disabled="false"@endif>
                    @foreach ($uitypes as $uitype)
                    <option value="{{ $uitype->name }}" @if((!$field->isFullyEditable) && $field->uitype === $uitype->name)selected="selected"@endif>{{ $uitype->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- Name --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.name') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg @if(!$field->isFullyEditable)text-gray-400 @endif">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" value="{{ $field->name }}" @if(!$field->isFullyEditable)disabled="disabled"@endif>
            </div>
        </div>
        {{-- Default --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.default') }}</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" wire:model="fields.{{ $index }}.default" @if(!$field->isEditable)disabled="disabled"@endif>
            </div>
        </div>
        {{-- Mandatory --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">{{ trans('module-designer::ui.block.config_columns.required') }}</div>
            <div class="h-10 pt-1 switch">
                <label>
                  <input type="checkbox" @if ($field->isRequired)checked="true"@endif wire:model="fields.{{ $index }}.isRequired" @if(!$field->isEditable)disabled="disabled"@endif>
                  <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                  {{ trans('module-designer::ui.block.config_columns.yes') }}
                </label>
              </div>
        </div>
    </div>
    <div class="grid grid-cols-3 mb-3">
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
                @if (!empty($fieldData[$option->key]))
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
                @endif

                <a class="w-12 px-2 py-1 mt-4 ml-4 text-center text-white cursor-pointer primary rounded-xl" wire:click="addRowToFieldOptionArray('{{ $field->name }}', '{{ $option->key }}')">
                    <i class="text-base material-icons">add</i>
                </a>
            @endif
        @endforeach
    </div>
    <div class="mt-3 mb-6 text-sm" x-show="open" x-on:click="advanced=true">
        <div class="text-right underline">
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
