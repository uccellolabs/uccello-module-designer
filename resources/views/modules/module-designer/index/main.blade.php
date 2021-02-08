@extends('layouts.uccello')

@section('page', 'index')

@section('extra-meta')
<meta name="field-config-url" content="{{ ucroute('module-designer.field.config', $domain, $module, ['uitype' => 'UITYPE']) }}">
<meta name="save-url" content="{{ ucroute('module-designer.save', $domain, $module) }}">
<meta name="install-url" content="{{ ucroute('module-designer.install', $domain, $module) }}">
@append


@section('content')
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
                        <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                    <div class="mt-8 mb-2 text-sm">Nom affiché dans l'URL</div>
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </x-md-vertical-step-card>

    <div class="my-10"></div>

    <x-md-vertical-step-card title="Créez vos colonnes">
        <div class="col-span-6 p-12">
            <div class="mb-2 text-sm">Ajoutez vos colonnes</div>
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <div class="grid grid-cols-4 gap-2">
                    <x-md-column-tag color="bg-red-200">Nom</x-md-column-tag>
                    <x-md-column-tag color="bg-blue-200">Prénom</x-md-column-tag>
                    <x-md-column-tag color="bg-green-200">Email</x-md-column-tag>
                    <x-md-column-tag color="bg-purple-200">Fonction</x-md-column-tag>
                    <x-md-column-tag color="bg-yellow-200">Adresse</x-md-column-tag>
                    <x-md-column-tag color="bg-indigo-200">Code postal</x-md-column-tag>
                </div>
            </div>

            <div class="mb-2 text-sm">Rendu liste</div>
            <div class="p-4 border border-gray-200 border-solid rounded-lg shadow-md">
                <div class="flex">
                    <div class="pt-3">
                        <i class="text-base material-icons">search</i>
                    </div>
                    <div class="grid flex-grow grid-flow-col gag-4">
                        <x-md-column color="bg-red-200">Nom</x-md-column>
                        <x-md-column color="bg-blue-200">Prénom</x-md-column>
                        <x-md-column color="bg-green-200">Email</x-md-column>
                        <x-md-column color="bg-purple-200">Fonction</x-md-column>
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
                <x-md-field-config color="bg-red-200">Nom</x-md-field-config>
                <x-md-field-config color="bg-blue-200">Prénom</x-md-field-config>
                <x-md-field-config color="bg-green-200">Email</x-md-field-config>
                <x-md-field-config color="bg-purple-200">Fonction</x-md-field-config>
            </div>
        </x-slot>
    </x-md-vertical-step-card>

    <div class="my-10"></div>

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
                    <x-md-detailed-field color="bg-red-200">Nom</x-md-detailed-field>
                    <x-md-detailed-field color="bg-blue-200">Prénom</x-md-detailed-field>
                    <x-md-detailed-field color="bg-green-200" large="true">Email</x-md-detailed-field>
                </div>
            </div>
        </div>
    </x-md-vertical-step-card>
@endsection

{{-- Script --}}
@section('extra-script')
{{ Html::script(mix('js/script.js', 'vendor/uccello/module-designer')) }}
@append

{{-- Styles --}}
@section('extra-css')
{{ Html::style(mix('css/styles.css', 'vendor/uccello/module-designer')) }}
@append
