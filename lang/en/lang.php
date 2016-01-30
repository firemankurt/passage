<?php
return [
    'plugin' => [//Plugin File
        'name' => 'Passage',
        'description' => 'Permission system for front-end user groups.',
        'backend_menu' => 'Passage Keys',
        'field_tab' => 'Passage Keys',
        'field_label' => 'Passage Keys',
        'field_commentAbove' => 'Check all keys (permissions) that you want this group to have.',
        'field_emptyOption' => 'There are no pasage keys, you should create some!',
        'permiss_label' => 'Manage key names for front-end user-group permissions.',
        'permiss_label_ug' => 'Manage front-end user-group permissions.',
    ],
    'keys_comp' => [//Controller
        'page_title' => 'Manage Passage Keys',
        'new' => 'New Key',
        'keys' => 'Keys',
        'return' => 'Return to keys list',
        '' => '',
    ],
    'key' => [// Model
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Description',
        'updated' => 'Updated',
        'created' => 'Created',
    ],
];
