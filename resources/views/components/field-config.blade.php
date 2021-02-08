<div x-data="{open: true, edit: false}">
    <div class="flex items-center">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $field->color }}"></div>
        {{-- Label --}}
        <span class="mr-1 text-sm font-semibold" x-show="!edit">{{ $field->label }}</span>
        {{-- Edit icon --}}
        <i class="text-base cursor-pointer material-icons">create</i>
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
                <select class="h-10 px-3 bg-transparent w-52 browser-default">
                    <option>Champ texte</option>
                    <option>Texte multiligne</option>
                    <option>Case à cocher</option>
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
                  <input type="checkbox" @if ($field->isMandatory)checked="true"@endif wire:click="toggleMandatory({{ $index }})">
                  <span class="lever" style="margin-left: 0; margin-right: 8px"></span>
                  Oui
                </label>
              </div>
        </div>
    </div>
    <div class="mt-3 mb-6 text-sm text-right underline" x-show="open">
        Paramètres avancées
    </div>
</div>
