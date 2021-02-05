@extends('layouts.uccello')

@section('page', 'index')

@section('extra-meta')
<meta name="field-config-url" content="{{ ucroute('module-designer.field.config', $domain, $module, ['uitype' => 'UITYPE']) }}">
<meta name="save-url" content="{{ ucroute('module-designer.save', $domain, $module) }}">
<meta name="install-url" content="{{ ucroute('module-designer.install', $domain, $module) }}">
@append


@section('content')

    <x-vertical-step-card title="Choisissez le nom de votre modèle de données">
        {{-- Left column --}}
        <div class="col-span-2 text-right">
            <div class="mb-2 text-sm">Icon</div>

        </div>
        {{-- Right column --}}
        <div class="col-span-3">
            <div class="mb-2 text-sm">Nom du modèle de données</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default">
            </div>
            <div class="mt-8 mb-2 text-sm">Nom affiché dans l'URL</div>
            <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <input type="text" class="w-full px-3 py-2 bg-transparent browser-default">
            </div>
        </div>
    </x-vertical-step-card>

    <div class="my-10"></div>

    <x-vertical-step-card title="Créez vos colonnes">
        <div class="col-span-6">
            <div class="mb-2 text-sm">Ajoutez vos colonnes</div>
            <div class="p-2 mb-6 bg-gray-100 border border-gray-200 border-solid rounded-lg">
                <div class="grid grid-cols-4 gap-2">
                    <x-md-column-tag color="bg-red-200">Nom</x-md-column-tag>
                    <x-md-column-tag color="bg-blue-200">Prénom</x-md-column-tag>
                    <x-md-column-tag color="bg-green-200">Email</x-md-column-tag>
                    <x-md-column-tag color="bg-purple-200">Fonction</x-md-column-tag>
                    <x-md-column-tag color="bg-yellow-200">Adresse</x-md-column-tag>
                    <x-md-column-tag color="bg-indigo-200">Code postal</x-md-column-tag>
                    <x-md-column-tag color="bg-blue-400">Ville</x-md-column-tag>
                    <x-md-column-tag color="bg-red-400">Pays</x-md-column-tag>
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

                    <div class="">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full primary">
                            <i class="text-base text-white material-icons">view_column</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-vertical-step-card>
@endsection

{{-- Script --}}
@section('extra-script')
{{ Html::script(mix('js/script.js', 'vendor/uccello/module-designer')) }}
@append

{{-- Styles --}}
@section('extra-css')
{{ Html::style(mix('css/styles.css', 'vendor/uccello/module-designer')) }}
@append
