<?php

return [
    'module-designer' => 'Module Designer',
    'block' => [
        'choose_action' => [
            'title' => 'Choisissez une action',
            'choose_action' => 'Que souhaitez-vous faire ?',
            'create_module' => 'Crééer un module',
            'edit_module' => 'Éditer un module',
            'delete_module' => 'Supprimer un module',
            'select_module' => 'Selectionnez le module à éditer :',
            'select_module_to_delete' => 'Selectionnez le module à supprimer :',
            'name_not_defined' => '(Sans nom)',
            'error' => [
                'no_modules' => 'Aucun module disponible.<br>Vous devez d\'abord créer un module avec Module Designer.',
            ],
        ],
        'create_module' => [
            'title' => 'Nommez votre module',
            'icon' => 'Icône',
            'module_name_plural' => 'Nom du module (pluriel)',
            'module_name_singular' => 'Nom du module (singulier)',
            'category' => 'Categorie',
            'name' => 'Slug',
        ],
        'config_module' => [
            'title' => 'Configuration avancée',
            'for_admin' => 'Module d\'administration',
            'yes' => 'Oui',
            'access_mode' => 'Qui aura accès aux données de ce module ?',
            'public' => 'Public',
            'private' => 'Privé',
            'public_description' => 'Tous les utilisateurs ayant accès à ce module dans ce workspace.',
            'private_description' => '- Ajout d\'un champ "Assigné à" dans vos colonnes<br>- Auront accès aux données : les administrateurs, les supérieurs hiérarchiques et tout utilisateur ou équipe assigné',
            'error' => [
                'name_alreay_used' => 'Nom déjà utilisé',
            ],
        ],
        'create_columns' => [
            'title' => 'Créez vos colonnes',
            'add_columns' => 'Ajoutez vos colonnes',
            'display_system_field' => 'Afficher un champ système',
            'column_name' => 'Nom de la colonne',
            'list_preview' => 'Rendu liste',
            'sort' => 'Trier',
            'display_hide' => 'Afficher / Cacher',
            'delete' => 'Supprimer',
            'delete_confirm' => 'Vraimer supprimer ?',
            'system_field' => [
                'created_by' => 'Créé par',
                'created_at' => 'Créé le',
                'updated_at' => 'Mis à jour le',
                'workspace' => 'Workspace',
            ],
            'error' => [
                'column_already_exists' => 'La colonne :column existe déjà',
                'column_name_reserved' => '":column" est un nom réservé. Vous ne pouvez pas l\'utiliser.',
                'no_displayed_columns' => 'Vous devez ajouter au moins une colonne',
            ],
        ],
        'config_columns' => [
            'title' => 'Configurez vos colonnes',
            'icon' => 'Icône',
            'uitype' => 'Type de champ',
            'name' => 'Nom système',
            'required' => 'Obligatoire',
            'default' => 'Valeur par défaut',
            'yes' => 'Oui',
            'array_value' => 'Valeur',
            'array_label' => 'Libellé',
            'advanced_params' => 'Paramètres avancés',
            'error' => [
                'no_fields_to_config' => 'Aucun champ à configurer',
            ],
        ],
        'define_label' => [
            'title' => 'Definissez votre libellé',
            'record_label' => 'Libellé',
        ],
        'config_detail' => [
            'title' => 'Configurez la vue détaillée',
            'add_block' => 'Ajouter un bloc',
            'tab_main' => 'Principal',
            'block_general' => 'Général',
            'block_system' => 'Système',
        ],
        'create_relatedlists' => [
            'title' => 'Créez des listes liées N-N',
        ],
    ],
    'field' => [
        'created_by' => 'Créé par',
        'created_at' => 'Créé le',
        'updated_at' => 'Modifié le',
        'workspace' => 'Workspace',
    ],
    'button' => [
        'continue' => 'Continuer',
        'finish' => 'Terminer',
        'delete' => 'Supprimer',
        'delete_confirm' => 'Vraiment supprimer ?',
    ],
];
