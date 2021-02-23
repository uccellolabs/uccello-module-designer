@extends('layouts.uccello')

@section('page', 'index')

@section('content')
    @livewire('module-designer')
@endsection

{{-- Script --}}
@section('extra-script')
    {{ Html::script(mix('js/script.js', 'vendor/uccello/module-designer')) }}
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@append

{{-- Styles --}}
@section('extra-css')
    {{ Html::style(mix('css/styles.css', 'vendor/uccello/module-designer')) }}
    @livewireStyles
@append
