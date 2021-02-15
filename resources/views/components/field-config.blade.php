<div x-data="{open: true, edit: false}">
    <div class="flex items-center">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $field->color }}"></div>
        {{-- Label --}}
        <span class="mr-1 text-sm font-semibold" x-show="!edit">{{ $field->label }}</span>
        <input type="text" x-show="edit" class="browser-default" @click.away="edit=false">
        {{-- Edit icon --}}
        <i class="text-base cursor-pointer material-icons" x-show="!edit" @click="edit=true">create</i>
        {{-- Line --}}
        <div class="flex-grow h-1 mx-3 border-b border-gray-400 border-solid"></div>
        {{--Less/More icon --}}
        <div class="flex items-center justify-center w-4 h-4 text-white rounded-full cursor-pointer primary" @click="open=!open">
            <i class="text-xs material-icons" x-show="open">expand_less</i>
            <i class="text-xs material-icons" x-show="!open">expand_more</i>
        </div>
    </div>
    <div class="flex items-center my-3" x-show="open">
        {{-- Icon --}}
        <div class="flex flex-col">
            <div class="mb-2 text-sm">Icône</div>
            <x-md-icon-selector></x-md-icon-selector>
        </div>
        {{-- Uitype --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">Type de champ</div>
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
            <div class="mb-2 text-sm">Nom système</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" value="{{ $field->name }}">
            </div>
        </div>
        {{-- Mandatory --}}
        <div class="flex flex-col ml-4">
            <div class="mb-2 text-sm">Obligatoire</div>
            <div class="h-10 pt-1 switch">
                <label>
                  <input type="checkbox" @if ($field->isMandatory)checked="true"@endif wire:model="fields.{{ $index }}.isMandatory">
                  <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                  Oui
                </label>
              </div>
        </div>
    </div>
    <div class="grid">
        @foreach ($field->options as $option)
            @php($option = (object) $option)

            <div class="flex flex-col w-1/3 ml-4">
            {{-- Input --}}
            @if (in_array($option->type, ['text', 'email', 'number', 'password']))
                <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                    <input type="{{ $option->type }}"
                        class="w-full px-3 py-2 bg-transparent browser-default"
                        wire:model="fields.{{ $index }}.data.{{ $option->key }}">
                </div>
            {{-- Boolean --}}
            @elseif ($option->type === 'boolean')
                <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                <div class="h-10 pt-1 switch">
                    <label>
                    <input type="checkbox" wire:model="fields.{{ $index }}.data.{{ $option->key }}">
                    <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                    Oui
                    </label>
                </div>
            {{-- Select --}}
            @elseif ($option->type === 'select')
                <div class="mb-2 text-sm">{{ $option->label ?? '' }}</div>
                <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                    <select class="w-full h-10 px-3 bg-transparent browser-default" wire:model="fields.{{ $index }}.data.{{ $option->key }}">
                        @foreach ($option->choices as $choice)
                            @php($choice = (object) $choice)
                            <option value="{{ $choice->key }}">{{ $choice->label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            </div>
        @endforeach
    </div>
    <div class="mt-3 mb-6 text-sm text-right underline" x-show="open">
        Paramètres avancées
    </div>
</div>
