<div class="p-4 mb-6 border border-gray-200 border-solid rounded-lg shadow-md">
    <div class="flex items-center mb-4">
        <div class="flex-grow text-sm font-semibold">
            {{ $block->translation }}
        </div>
        <div>
            <i class="float-right text-2xl material-icons">expand_less</i>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach($fields->sortBy('sequence') as $index => $field)
            @continue(((object) $field)->block_uuid !== $block->uuid)
            <x-md-detailed-field :field="$field" :index="$index"></x-md-detailed-field>
        @endforeach
    </div>
    {{-- Add field --}}
    @if ($areAvailableFields)
    <div class="grid mt-4">
        <div x-data="{open:false}" class="relative flex items-center justify-self-center">
            <button @mouseenter="open=true" @mouseleave="open=false" class="px-2 py-1 text-sm font-semibold text-blue-400 border border-blue-400 border-solid rounded-lg hover:bg-blue-50">
                Insérer un champ
                <i class="ml-1 text-sm material-icons">subject</i>
            </button>

            <div x-show.transition="open" @mouseenter="open=true" @mouseleave="open=false" class="absolute w-40 px-3 py-2 ml-2 bg-white border border-gray-300 border-solid rounded-lg left-full">
                @foreach($fields->sortBy('sequence') as $field)
                    @continue(((object) $field)->block_uuid)
                    <x-md-dropdown-field :block="$block" :field="$field"></x-md-dropdown-field>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
