<?php

return [
    'plugin' => [//Plugin File
        'name' => 'Passagem',
        'description' => 'Sistema de permissão para grupos de usuários front-end.',
        'backend_menu' => 'Chaves de passagem',
        'field_tab' => 'Chaves de passagem',
        'field_label' => 'Chaves de passagem',
        'field_commentAbove' => 'Verifique todas as chaves (permissões) que você deseja que este grupo tenha.',
        'field_emptyOption' => 'Não há chaves de passagem, você deve criar algumas!',
        'permiss_label' => 'Gerenciar nomes de chave para as permissões do grupo de usuários do front-end.',
        'permiss_label_ug' => 'Gerencie permissões front-end do grupo de usuários.',
    ],
    'keys_comp' => [//Controller
        'page_title' => 'Gerenciar chaves de passagem',
        'new' => 'Nova Chave',
        'keys' => 'Chaves',
        'return' => 'Voltar à lista de Chaves',
        '' => '',
    ],
    'key' => [// Model
        'id' => 'ID',
        'name' => 'Nome',
        'description' => 'Descrição',
        'updated' => 'Atualizado',
        'created' => 'Criado',
    ],
];
