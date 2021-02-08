@extends('layouts.uccello')

@section('page', 'index')

@section('extra-meta')
    <meta name="field-config-url" content="{{ ucroute('module-designer.field.config', $domain, $module, ['uitype' => 'UITYPE']) }}">
    <meta name="save-url" content="{{ ucroute('module-designer.save', $domain, $module) }}">
    <meta name="install-url" content="{{ ucroute('module-designer.install', $domain, $module) }}">
@append


@section('content')
    @livewire('module-designer')
@endsection

{{-- Script --}}
@section('extra-script')
    {{ Html::script(mix('js/script.js', 'vendor/uccello/module-designer')) }}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js"></script>
    @livewireScripts
@append

{{-- Styles --}}
@section('extra-css')
    {{ Html::style(mix('css/styles.css', 'vendor/uccello/module-designer')) }}
    @livewireStyles
@append
