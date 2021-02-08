<div>
    <x-md-vertical-step-card title="Choisissez le nom de votre modèle de données">
        <div class="flex p-12">
            {{-- Left column --}}
            <div class="w-2/6 mr-4">
                <div class="mb-2 mr-10 text-sm text-right">Icône</div>
                <div class="float-right">
                    <x-md-icon-selector></x-md-icon-selector>
                </div>
            </div>
            {{-- Right column --}}
            <div class="w-3/6">
                <div class="">
                    <div class="mb-2 text-sm">Nom du modèle de données</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="label" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                    <div class="mt-8 mb-2 text-sm">Nom affiché dans l'URL</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" wire:model="name" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </x-md-vertical-step-card>

    <div class="grid">
        <div class="grid flex-col justify-self-center">
            <div class="grid items-center w-5 h-5 bg-white border border-gray-200 border-solid rounded-full">
                <div class="w-2 h-2 bg-blue-500 rounded-full justify-self-center"></div>
            </div>
            <div class="w-0 h-8 border-l border-blue-500 border-solid justify-self-center"></div>
            <div class="grid items-center w-5 h-5 bg-white border border-gray-200 border-solid rounded-full">
                <div class="w-2 h-2 bg-blue-500 rounded-full justify-self-center"></div>
            </div>
        </div>
    </div>

    <x-md-vertical-step-card title="Créez vos colonnes">
        <div class="col-span-6 p-12">
            <div class="mb-2 text-sm">Ajoutez vos colonnes</div>
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <div class="grid grid-cols-4 gap-2">
                    @foreach($fields as $field)
                        <x-md-column-tag :field="$field"></x-md-column-tag>
                    @endforeach

                    <input type="text" wire:model="column" placeholder="Nom de la colonne" class="px-2 bg-transparent browser-default focus:outline-none" autocomplete="false" wire:keydown.enter="createField()">
                </div>
            </div>

            <div class="mb-2 text-sm">Rendu liste</div>
            <div class="p-4 border border-gray-200 border-solid rounded-lg shadow-md">
                <div class="flex">
                    <div class="pt-3">
                        <i class="text-base material-icons">search</i>
                    </div>
                    <div class="grid flex-grow grid-flow-col gag-4">
                        @forelse($fields as $field)
                            <x-md-column :field="$field"></x-md-column>
                        @empty
                            <x-md-column color="bg-red-200">Colonne 1</x-md-column>
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

        <x-slot name="after">
            <div class="border-t border-gray-200 border-solid justify-self-center">
                <x-md-vertical-step-card-title title="Configurez vos colonnes" close="true"></x-vertical-step-card-title>
            </div>
            <div class="p-6">
                @foreach($fields as $index => $field)
                    <x-md-field-config :field="$field" :index="$index"></x-md-field-config>
                @endforeach
            </div>
        </x-slot>
    </x-md-vertical-step-card>

    <div class="grid">
        <div class="grid flex-col justify-self-center">
            <div class="grid items-center w-5 h-5 bg-white border border-gray-200 border-solid rounded-full">
                <div class="w-2 h-2 bg-blue-500 rounded-full justify-self-center"></div>
            </div>
            <div class="w-0 h-8 border-l border-blue-500 border-solid justify-self-center"></div>
            <div class="grid items-center w-5 h-5 bg-white border border-gray-200 border-solid rounded-full">
                <div class="w-2 h-2 bg-blue-500 rounded-full justify-self-center"></div>
            </div>
        </div>
    </div>

    <x-md-vertical-step-card title="Configurez la fiche détaillée">
        <div class="p-6">
            <div class="p-4 border border-gray-200 border-solid rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="flex-grow text-sm font-semibold">
                        Informations générales
                    </div>
                    <div>
                        <i class="float-right text-2xl material-icons">expand_less</i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($fields as $index => $field)
                        <x-md-detailed-field :field="$field" :index="$index"></x-md-detailed-field>
                    @endforeach
                </div>
            </div>
        </div>
    </x-md-vertical-step-card>
</div>
