@extends('layouts.uccello')

@section('page', 'index')

@section('breadcrumb')
<div class="nav-wrapper">
    <div class="col s12">
        <div class="breadcrumb-container left">
            {{-- Admin --}}
            @if ($admin_env)
            <span class="breadcrumb">
                <a class="btn-flat" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                    <i class="material-icons left">settings</i>
                    <span class="hide-on-small-only">{{ uctrans('breadcrumb.admin', $module) }}</span>
                </a>
            </span>
            @endif

            {{-- Module icon --}}
            <span class="breadcrumb">
                <a class="btn-flat" href="{{ ucroute($module->defaultRoute, $domain, $module) }}">
                    <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                    <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                </a>
            </span>

            <span class="breadcrumb active">
                {{ uctrans('label.creation', $module) }}
            </span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top: 15px">
    {{-- Tab links --}}
    <div class="col s12">
        <ul class="tabs transparent">
            <li class="tab"><a href="#module" class="active">1. {{ uctrans('tab.module', $module) }}</a></li>
            <li class="tab"><a href="#block-field">2. {{ uctrans('tab.block_field', $module) }}</a></li>
            <li class="tab"><a href="#filter">3. {{ uctrans('tab.filter', $module) }}</a></li>
            <li class="tab"><a href="#widget">4. {{ uctrans('tab.widget', $module) }}</a></li>
            <li class="tab"><a href="#relation">5. {{ uctrans('tab.relation', $module) }}</a></li>
            <li class="tab"><a href="#translation">6. {{ uctrans('tab.translation', $module) }}</a></li>
            <li class="tab"><a href="#install">7. {{ uctrans('tab.install', $module) }}</a></li>
        </ul>
    </div>

    {{-- Module --}}
    <div id="module" class="col s12" style="margin-bottom: 50px">
        <div class="card">
            <div class="card-content">
                <span class="card-title"><i class="material-icons left primary-text">extension</i>Documents</span>

                <div class="row">
                    {{-- Icon --}}
                    <div class="input-field col s12 m1">
                        <label for="module_icon" class="active">{{ uctrans('field.module.icon', $module) }}</label>
                        <div style="margin-top: 15px">
                            <a class="btn-floating waves-effect waves-light grey lighten-3"><i class="material-icons primary-text">extension</i></a>
                        </div>
                    </div>

                    {{-- Label --}}
                    <div class="input-field col s12 m5">
                        <input type="text" id="module_label" value="Documents">
                        <label for="module_label">{{ uctrans('field.module.label', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.module.label', $module) }}</small>
                    </div>

                    {{-- Name --}}
                    <div class="input-field col s12 m6">
                        <input type="text" id="module_name" value="document">
                        <label for="module_name">{{ uctrans('field.module.name', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.module.name', $module) }}</small>
                    </div>

                    {{-- Visibility --}}
                    <div class="input-field col s12 m6">
                        <select id="module_visibility">
                            <option value="public">{{ uctrans('visibility.public', $module) }}</option>
                            <option value="private">{{ uctrans('visibility.private', $module) }}</option>
                        </select>
                        <label for="module_visibility">{{ uctrans('field.module.visibility', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.module.visibility', $module) !!}</small>
                    </div>

                    {{-- For admin --}}
                    <div class="input-field col s12 m6">
                        {{-- <select id="module_admin" data-tooltip="{{ uctrans('info.module.admin', $module) }}" data-position="bottom">
                            <option value="0">{{ uctrans('no', $module) }}</option>
                            <option value="1">{{ uctrans('yes', $module) }}</option>
                        </select> --}}
                        <label for="module_admin" class="active">{{ uctrans('field.module.admin', $module) }}</label>
                        <p style="margin-top: 15px; margin-bottom: 15px">
                            <label>
                              <input type="checkbox" />
                              <span>{{ uctrans('yes', $module) }}</span>
                            </label>
                        </p>
                        <small class="help primary-text">{{ uctrans('info.module.admin', $module) }}</small>
                    </div>

                    {{-- Package --}}
                    @if (config('module-designer-ui.can_choose_package'))
                    <div class="input-field col s12 m6">
                        <select id="module_package">
                            <option value="app">{{ uctrans('label.application', $module) }}</option>
                            <option value="uccello/document-designer">uccello/document-designer</option>
                        </select>
                        <label for="module_package">{{ uctrans('field.module.package', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.module.package', $module) }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#block-field" class="waves-effect waves-light btn primary tab-trigger">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Blocs and fields --}}
    <div id="block-field" class="col s12" style="margin-bottom: 50px">
        <div class="card">
            <div class="card-content">
                <span class="card-title"><i class="material-icons primary-text left">info</i>{{ uctrans('block.general', $module) }}</span>

                <div class="row">
                    {{-- Icon --}}
                    <div class="input-field col s12 m1">
                        <label for="block1_icon" class="active">{{ uctrans('field.block.icon', $module) }}</label>
                        <div style="margin-top: 15px">
                            <a class="btn-floating waves-effect waves-light grey lighten-3"><i class="material-icons primary-text">info</i></a>
                        </div>
                    </div>

                    {{-- Label --}}
                    <div class="input-field col s12 m5">
                        <input type="text" id="block1_label" value="{{ uctrans('block.general', $module) }}">
                        <label for="block1_label">{{ uctrans('field.block.label', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.block.label', $module) }}</small>
                    </div>

                    {{-- Name --}}
                    <div class="input-field col s12 m6">
                        <input type="text" id="block1_name" value="general">
                        <label for="block1_name">{{ uctrans('field.block.name', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.block.name', $module) }}</small>
                    </div>
                </div>

                {{-- Fields --}}
                <div class="card fields-list">
                    <div class="card-content">
                        <span class="card-title">{{ uctrans('label.fields', $module) }}</span>

                        <div class="row">
                            <div class="col s12 m6">
                                <div class="module-field">Nom</div>
                            </div>
                            <div class="col s12 m6">
                                <div class="module-field">Prénom</div>
                            </div>
                            <div class="col s12">
                                <div class="module-field"><i class="material-icons primary-text left">description</i>Decription</div>
                            </div>

                            {{-- Add field --}}
                            <div class="col s12">
                                <a class="btn waves-effect waves-light primary" style="margin-top: 10px"><i class="material-icons white-text left">add</i>{{ uctrans('button.add_field', $module) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#module" class="waves-effect waves-light btn orange tab-trigger left"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
            <a href="#filter" class="waves-effect waves-light btn primary tab-trigger ig">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Filters --}}
    <div id="filter" class="col s12" style="margin-bottom: 50px">
        <div class="card filter">
            <div class="card-content">
                <div class="card-title">
                    <i class="material-icons primary-text left">table_chart</i>{{ uctrans('label.filter_all', $module) }}

                    <div class="switch right">
                        <label>
                            {{ uctrans('field.filter.default', $module) }}
                            <input type="checkbox" checked="checked">
                            <span class="lever"></span>
                        </label>
                      </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <div class="chip">
                            <i class="fa fa-sort-amount-up primary-text" style="margin-right: 10px"></i>
                            Nom
                            <i class="close material-icons">close</i>
                        </div>

                        <div class="chip">
                            Prénom
                            <i class="close material-icons">close</i>
                        </div>

                        <div class="chip">
                            Date de naissance
                            <i class="close material-icons">close</i>
                        </div>

                        {{-- Add button --}}
                        <a class="dropdown-trigger btn-floating waves-effect waves-light primary" href="#" data-target="dropdown1"><i class="material-icons">add</i></a>
                        <ul id="dropdown1" class="dropdown-content">
                            <li><a href="#!">Email</a></li>
                            <li><a href="#!">Téléphone</a></li>
                        </ul>
                    </div>
                </div>

                <div class="card conditions-list">
                    <div class="card-content">
                        <span class="card-title">{{ uctrans('label.conditions', $module) }}</span>

                        <div class="row">
                            {{-- Field --}}
                            <div class="input-field col s12 m6">
                                <select id="filter1_field">
                                    <option value="app">Nom</option>
                                </select>
                                <label for="filter1_field">{{ uctrans('field.filter.field', $module) }}</label>
                                {{-- <small class="help primary-text">{{ uctrans('info.filter.field', $module) }}</small> --}}
                            </div>

                            {{-- Value --}}
                            <div class="input-field col s12 m5">
                                <input type="text" id="filter1_value" />
                                <label for="filter1_value">{{ uctrans('field.filter.value', $module) }}</label>
                                {{-- <small class="help primary-text">{{ uctrans('info.filter.value', $module) }}</small> --}}
                            </div>

                            {{-- Delete --}}
                            <div class="input-field col s12 m1 right-align">
                                <a class="btn-floating waves-effect waves-light red" style="margin-top: 10px"><i class="material-icons white-text">delete</i></a>
                            </div>

                            {{-- Add condition --}}
                            <div class="input-field col s12">
                                <a class="btn waves-effect waves-light primary" style="margin-top: 10px"><i class="material-icons white-text left">add</i>{{ uctrans('button.add_condition', $module) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#block-field" class="waves-effect waves-light btn orange tab-trigger left"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
            <a href="#widget" class="waves-effect waves-light btn primary tab-trigger">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Widgets --}}
    <div id="widget" class="col s12" style="margin-bottom: 50px">
        <div class="card widget">
            <div class="card-content">
                <span class="card-title"><i class="material-icons primary-text left">widgets</i>{{ uctrans('label.summary', $module) }}</span>

                <div class="row">
                    <div class="col s12">
                        <div class="chip">
                            Champs principaux
                            <i class="close material-icons">close</i>
                        </div>

                        {{-- Add button --}}
                        <a class="dropdown-trigger btn-floating waves-effect waves-light primary" href="#" data-target="dropdown2"><i class="material-icons">add</i></a>
                        <ul id="dropdown2" class="dropdown-content">
                            <li><a href="#!">Contacts</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#filter" class="waves-effect waves-light btn orange tab-trigger left"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
            <a href="#relation" class="waves-effect waves-light btn primary tab-trigger">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Relations --}}
    <div id="relation" class="col s12" style="margin-bottom: 50px">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left primary-text">domain</i>Comptes
                    {{-- <small>1-n</small> --}}
                </span>

                <div class="row">
                    {{-- Type --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_type">
                            <option value="1-n">{{ uctrans('relation.1_n', $module) }}</option>
                            <option value="n-1">{{ uctrans('relation.n_1', $module) }}</option>
                            <option value="n-n">{{ uctrans('relation.n_n', $module) }}</option>
                        </select>
                        <label for="relation1_type">{{ uctrans('field.relation.type', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.relation.type', $module) !!}</small>
                    </div>

                    {{-- Display mode --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_display_mode">
                            <option value="tab">{{ uctrans('label.tab', $module) }}</option>
                            <option value="block">{{ uctrans('label.block', $module) }}</option>
                        </select>
                        <label for="relation1_display_mode">{{ uctrans('field.relation.display_mode', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.relation.display_mode', $module) !!}</small>
                    </div>

                    {{-- Source module --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_source_module">
                            @foreach ($modules as $_module)
                                <option value="document">Document</option>
                                @foreach ($modules as $_module)
                                    <option value="{{ $_module->name }}">{{ uctrans($_module->name, $_module) }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        <label for="relation1_source_module">{{ uctrans('field.relation.source_module', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.relation.source_module', $module) !!}</small>
                    </div>

                    {{-- Target module --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_target_module">
                            @foreach ($modules as $_module)
                                <option value="{{ $_module->name }}">{{ uctrans($_module->name, $_module) }}</option>
                            @endforeach
                        </select>
                        <label for="relation1_target_module">{{ uctrans('field.relation.target_module', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.relation.target_module', $module) !!}</small>
                    </div>

                    {{-- Label --}}
                    <div class="input-field col s12 m6">
                        <input type="text" id="relation1_label" value="Comptes">
                        <label for="relation1_label">{{ uctrans('field.module.label', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.relation.label', $module) }}</small>
                    </div>

                    {{-- Related field --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_related_field">
                            <option value="account">Compte</option>
                        </select>
                        <label for="relation1_related_field">{{ uctrans('field.relation.related_field', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.relation.related_field', $module) }}</small>
                    </div>

                    {{-- Actions --}}
                    <div class="input-field col s12 m6">
                        <select id="relation1_action" multiple>
                            <option value="select">{{ uctrans('action.select', $module) }}</option>
                            <option value="add">{{ uctrans('action.add', $module) }}</option>
                        </select>
                        <label for="relation1_action">{{ uctrans('field.relation.action', $module) }}</label>
                        <small class="help primary-text">{!! uctrans('info.relation.action', $module) !!}</small>
                    </div>

                    {{-- Method --}}
                    @if (config('module-designer-ui.can_choose_relation_method'))
                    <div class="input-field col s12 m6">
                        <input type="text" id="relation1_method" value="getDependentList">
                        <label for="relation1_method">{{ uctrans('field.relation.method', $module) }}</label>
                        <small class="help primary-text">{{ uctrans('info.relation.method', $module) }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#widget" class="waves-effect waves-light btn orange tab-trigger left"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
            <a href="#translation" class="waves-effect waves-light btn primary tab-trigger">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Tranlation --}}
    <div id="translation" class="col s12" style="margin-bottom: 50px">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab"><a href="#fr" class="active">fr</a></li>
                            <li class="tab"><a href="javascrip:void(0)"><i class="material-icons primary-text left">add</i></a></li>
                        </ul>
                    </div>
                    {{-- FR --}}
                    <div id="fr" class="col s12">
                        <ul class="collapsible" style="margin-top: 25px">
                            {{-- Module --}}
                            <li class="active">
                                <div class="collapsible-header"><i class="material-icons">extension</i>{{ uctrans('translation.module', $module) }}</div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_module_name" value="Documents">
                                            <label for="trans_fr_module_name">document</label>
                                            <small class="help primary-text">fr : Documents</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {{-- Block & Fields --}}
                            <li>
                                <div class="collapsible-header"><i class="material-icons">info</i>{{ uctrans('translation.block', $module) }} #1</div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="col s12">
                                            <h6><b>{{ uctrans('translation.block', $module) }}</b></h6>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_block1_label" value="Informations générales">
                                            <label for="trans_fr_block1_label">block.general</label>
                                            <small class="help primary-text">fr : Informations générales</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12">
                                            <h6><b>{{ uctrans('translation.fields', $module) }}</b></h6>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_block1_field1" value="Prénom">
                                            <label for="trans_fr_block1_field1">field.firstname</label>
                                            <small class="help primary-text">fr : Prénom</small>
                                        </div>

                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_block1_field2" value="Nom">
                                            <label for="trans_fr_block1_field2">field.lastname</label>
                                            <small class="help primary-text">fr : Nom</small>
                                        </div>

                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_block1_field3" value="Description">
                                            <label for="trans_fr_block1_field3">field.description</label>
                                            <small class="help primary-text">fr : Description</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {{-- Filters --}}
                            <li>
                                <div class="collapsible-header"><i class="material-icons">filter_alt</i>{{ uctrans('translation.filters', $module) }}</div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_filter1" value="Tout">
                                            <label for="trans_fr_filter1">filter.all</label>
                                            <small class="help primary-text">fr : Tout</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {{-- Relations --}}
                            <li>
                                <div class="collapsible-header"><i class="material-icons">domain</i>{{ uctrans('translation.relations', $module) }}</div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <input type="text" id="trans_fr_relation1" value="Comptes">
                                            <label for="trans_fr_relation1">relatedlist.account</label>
                                            <small class="help primary-text">fr : Comptes</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="right-align">
            <a href="#relation" class="waves-effect waves-light btn orange tab-trigger left"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
            <a href="#install" class="waves-effect waves-light btn primary tab-trigger">{{ uctrans('button.next', $module) }}<i class="material-icons right">chevron_right</i></a>
        </div> --}}
    </div>

    {{-- Install --}}
    <div id="install" class="col s12" style="margin-bottom: 50px">
        <div class="card">
            <div class="card-content">
                <span class="card-title"><i class="material-icons primary-text left">input</i>{{ uctrans('label.install', $module) }}</span>

                <div class="row">
                    <div class="col s12 center-align">
                        <h5>
                            {{ uctrans('install.ready', $module) }}
                        </h5>

                        <button class="btn waves-effect waves-light green">
                            <i class="material-icons left">input</i>
                            {{ uctrans('button.install', $module) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="left-align">
            <a href="#translation" class="waves-effect waves-light btn orange tab-trigger"><i class="material-icons left">chevron_left</i>{{ uctrans('button.previous', $module) }}</a>
        </div> --}}
    </div>

    {{-- Page buttons --}}
    @section('page-action-buttons')
        {{-- Save button --}}
        @if (Auth::user()->canAdmin($domain, $module))
        <div id="page-action-buttons">
            {{-- Previous step --}}
            {{-- <a href="javascript:void(0)"
                class="btn-floating btn-large modal-trigger orange previous"
                data-tooltip="{{ uctrans('button.previous_step', $module) }}"
                data-position="top">
                <i class="material-icons">arrow_back_ios</i>
            </a> --}}

            {{-- Add --}}
            <a href="javascript:void(0)"
                class="btn-floating btn-large modal-trigger primary previous"
                data-tooltip="{{ uctrans('button.add_block', $module) }}"
                data-position="top">
                <i class="material-icons">add</i>
            </a>

            {{-- Next step --}}
            {{-- <a href="javascript:void(0)"
                class="btn-floating btn-large modal-trigger primary next"
                data-tooltip="{{ uctrans('button.next_step', $module) }}"
                data-position="top">
                <i class="material-icons">arrow_forward_ios</i>
            </a> --}}
        </div>
        @endif
    @show
</div>
@endsection

{{-- Script --}}
@section('extra-script')
    {{ Html::script(mix('js/script.js', 'vendor/uccello/module-designer-ui')) }}
@append

{{-- Styles --}}
@section('extra-css')
    {{ Html::style(mix('css/styles.css', 'vendor/uccello/module-designer-ui')) }}
@append
