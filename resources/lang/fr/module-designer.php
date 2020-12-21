<?php

return [
    'module-designer' => 'Module Designer',
    'tab' => [
        'module' => 'Module',
        'block_field' => 'Blocs & Champs',
        'filter' => 'Filtres',
        'widget' => 'Widgets',
        'relation' => 'Relations',
        'translation' => 'Traduction',
        'install' => 'Installer',
    ],
    'button' => [
        'previous' => 'Précédent',
        'next' => 'Suivant',
        'add_field' => 'Ajouter un champ',
        'add_condition' => 'Ajouter une condition',
        'install' => 'Installer le module',
        'add_block' => 'Ajouter un Bloc',
    ],
    'label' => [
        'creation' => 'Création',
        'module' => 'Module',
        'application' => 'Application',
        'fields' => 'Champs',
        'filter_all' => 'Tout',
        'conditions' => 'Conditions',
        'summary' => 'Widgets de la vue "Résumé"',
        'tab' => 'Onglet',
        'block' => 'Bloc',
        'translation' => 'Traduction',
        'install' => 'Installer',
    ],
    'field' => [
        'module' => [
            'icon' => 'Icône',
            'label' => 'Libellé',
            'name' => 'Nom système',
            'admin' => 'Module d\'administration',
            'visibility' => 'Visibilité',
            'package' => 'Package',
        ],
        'block' => [
            'label' => 'Libellé',
            'name' => 'Nom système',
            'icon' => 'Icône',
        ],
        'filter' => [
            'default' => 'Par défaut',
            'field' => 'Champ',
            'value' => 'Valeur',
        ],
        'relation' => [
            'type' => 'Type',
            'target_module' => 'Module cible',
            'source_module' => 'Module source',
            'display_mode' => 'Mode d\'affichage',
            'related_field' => 'Champ lié',
            'action' => 'Action autorisées',
            'method' => 'Méthode',
        ],
    ],
    'info' => [
        'module' => [
            'label' => 'Nom affiché dans le menu (pluriel).',
            'name' => 'Nom utilisé par le système.',
            'admin' => 'Si oui, le module module sera accessible depuis le panneau d\'administration.',
            'visibility' => '<b>Public :</b> Tout le monde voit toutes les données. <b>Privé :</b> Les données dépendent des droits.',
            'package' => 'Permet de définir si le module fera partie d\'un package indépendant.',
        ],
        'block' => [
            'label' => 'Nom affiché dans le formulaire.',
            'name' => 'Nom utilisé par le système.',
            'icon' => 'Modifier',
        ],
        'relation' => [
            'type' => '<b>1-N</b> et <b>N-N :</b> La relation sera créée dans ce module. <b>N-1 :</b> La relation sera ajoutée au module source.',
            'display_mode' => '<b>Onglet :</b> Présenté sous forme d\'onglet. <b>Bloc :</b> Présenté sous forme de bloc virtuel.',
            'source_module' => 'Module dans lequel sera créée la relation.',
            'target_module' => 'Module vers lequel pointe la relation.',
            'label' => 'Nom de la relation qui sera affiché.',
            'related_field' => 'Champ qui crée la relation vers le module cible.',
            'action' => 'Actions autorisées depuis la relation. Les boutons correspondants seront affichés.',
            'method' => 'Méthode qui sera utilisée pour retrouver les relations.'
        ],
    ],
    'translation' => [
        'module' => 'Module',
        'blocks_fields' => 'Blocs & Champs',
        'block' => 'Bloc',
        'fields' => 'Champs',
        'filters' => 'Filtres',
        'relations' => 'Relations',
    ],
    'install' => [
        'ready' => 'Prêt à installer ?',
    ],
    'visibility' => [
        'public' => 'Public',
        'private' => 'Privé',
    ],
    'relation' => [
        '1_n' => '1-N : Un vers Plusieurs',
        'n_1' => 'N-1 : Plusieurs vers Un',
        'n_n' => 'N-N : Plusieurs vers Plusieurs',
    ],
    'action' => [
        'select' => 'Sélectionner',
        'add' => 'Ajouter',
    ],
];
