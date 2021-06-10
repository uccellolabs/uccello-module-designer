<?php

return [
    'module-designer' => 'Module Designer',
    'block' => [
        'choose_action' => [
            'title' => 'Choose an action',
            'choose_action' => 'What do you want to do?',
            'create_module' => 'Create a module',
            'edit_module' => 'Edit a module',
            'delete_module' => 'Delete a module',
            'select_module' => 'Select the module to edit:',
            'select_module_to_delete' => 'Select the module to delete:',
            'name_not_defined' => '(Empty name)',
            'error' => [
                'no_modules' => 'No modules are available.<br>You must first create one with Module Designer.',
            ],
        ],
        'create_module' => [
            'title' => 'Name your module',
            'icon' => 'Icon',
            'module_name_plural' => 'Module name (plural)',
            'module_name_singular' => 'Module name (singular)',
            'category' => 'Category',
            'name' => 'Slug',
        ],
        'config_module' => [
            'title' => 'Advanced configuration',
            'for_admin' => 'Administration module',
            'yes' => 'Yes',
            'access_mode' => 'Who can access to module data?',
            'public' => 'Public',
            'private' => 'Private',
            'public_description' => 'All users with access to this module in this workspace will be able to see the records.',
            'private_description' => '- Add an "Assigned to" field in your columns<br>- Will have access to the data: administrators, supervisors and any assigned user or team',
            'error' => [
                'name_alreay_used' => 'Name already used',
            ],
        ],
        'create_columns' => [
            'title' => 'Create your columns',
            'add_columns' => 'Add your columns',
            'display_system_field' => 'Display a system field',
            'column_name' => 'Column name',
            'list_preview' => 'List preview',
            'sort' => 'Sort',
            'display_hide' => 'Display / Hide',
            'delete' => 'Delete',
            'delete_confirm' => 'Realy delete?',
            'system_field' => [
                'created_by' => 'Created by',
                'created_at' => 'Created at',
                'updated_at' => 'Updated at',
                'workspace' => 'Workspace',
            ],
            'error' => [
                'column_already_exists' => 'Column :column already exists',
                'column_name_reserved' => '":column" is a reserved name. You cannot use it.',
                'no_displayed_columns' => 'You must add at least one column',
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
            'error' => [
                'no_fields_to_config' => 'No fields to configure',
            ],
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
        'created_by' => 'Created by',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'workspace' => 'Workspace',
    ],
    'button' => [
        'continue' => 'Continue',
        'finish' => 'Finish',
        'delete' => 'Delete',
        'delete_confirm' => 'Really delete?',
    ],
];
