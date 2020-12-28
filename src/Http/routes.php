<?php

Route::middleware('web', 'auth')
->namespace('Uccello\ModuleDesignerUi\Http\Controllers')
->name('module-designer-ui.')
->group(function() {

    // This makes it possible to adapt the parameters according to the use or not of the multi domains
    if (!uccello()->useMultiDomains()) {
        $domainParam = '';
        $domainAndModuleParams = '{module}';
    } else {
        $domainParam = '{domain}';
        $domainAndModuleParams = '{domain}/{module}';
    }

    // Example 1
    // {domain}/{module}/my_path => This route is available for all modules in all domains
    // Route::get($domainAndModuleParams.'/my_path', 'MyController@action')->name('my_path');

    // Example 2
    // {domain}/home/my_path => This route forces to use 'home' module and is available in all domains
    // Route::get($domainParam.'/home/my_path', 'MyController@action')->defaults('module', 'home')->name('home.my_path');

    Route::get($domainParam.'/module-designer', 'ModuleDesigner\IndexController@process')
        ->defaults('module', 'module-designer')
        ->name('index');

    Route::get($domainParam.'/module-designer/field/config', 'ModuleDesigner\IndexController@fieldConfig')
        ->defaults('module', 'module-designer')
        ->name('field.config');

    Route::post($domainParam.'/module-designer/save', 'ModuleDesigner\IndexController@save')
        ->defaults('module', 'module-designer')
        ->name('save');

    Route::get($domainParam.'/module-designer/install_module', 'ModuleDesigner\IndexController@install')
        ->defaults('module', 'module-designer')
        ->name('install');
});
