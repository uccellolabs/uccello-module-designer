<?php

return [
    'module-designer' => 'Module Designer',
    'block' => [
        'choose_action' => [
            'title' => 'Choose an action',
            'choose_action' => 'What do you want to do?',
            'create_module' => 'Create a module',
            'edit_module' => 'Edit a module',
            'continue_creation' => 'Continue a creation',
            'select_module' => 'Select the module to edit:',
            'select_designed_module' => 'Select the module to continue with:',
            'name_not_defined' => '(Empty name)',
        ],
        'create_module' => [
            'title' => 'Name your module',
            'icon' => 'Icon',
            'module_name_plural' => 'Module name (plural)',
            'module_name_singular' => 'Module name (singular)',
            'category' => 'Category',
            'slug' => 'Slug',
        ],
        'config_module' => [
            'title' => 'Advanced configuration',
            'for_admin' => 'Administration module',
            'yes' => 'Yes',
            'public' => 'Public',
            'private' => 'Private',
            'public_description' => 'All users with access to this module in this workspace will be able to see the records.',
            'private_description' => '- Add an "Assigned to" field in your columns<br>- Will have access to the data: administrators, supervisors and any assigned user or team',
        ],
        'create_columns' => [
            'title' => 'Create your columns',
            'add_columns' => 'Add your columns',
            'display_system_field' => 'Display a system field',
            'column_name' => 'Column name',
            'list_preview' => 'List preview',
            'system_field' => [
                'created_by' => 'Created by',
                'created_at' => 'Created at',
                'updated_at' => 'Updated at',
                'workspace' => 'Workspace',
            ],
        ],
        'config_columns' => [
            'title' => 'Configure your columns',
            'icon' => 'Icon',
            'uitype' => 'Field type',
            'name' => 'System name',
            'required' => 'Required',
            'default' => 'Valeur par défaut',
            'yes' => 'Yes',
            'array_value' => 'Value',
            'array_label' => 'Label',
            'advanced_params' => 'Advanced',
        ],
        'define_label' => [
            'title' => 'Define your label',
            'record_label' => 'Label',
        ],
        'config_detail' => [
            'title' => 'Configure the detail view',
            'add_block' => 'Add a block',
            'tab_main' => 'Main',
            'block_general' => 'General',
            'block_system' => 'System',
        ],
        'create_relatedlists' => [
            'title' => 'Create N-N Related Lists',
        ],
    ],
    'field' => [
        'assigned_to' => 'Assigned to',
        'workspace' => 'Workspace',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],
];
