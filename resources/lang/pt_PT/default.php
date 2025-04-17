<?php

return [
    'title' => 'Editar Perfil',
    'profile_information' => 'Informação do Perfil',
    'profile_information_description' => 'Atualize as informações do seu perfil e endereço de email.',
    'name' => 'Nome',
    'email' => 'Email',
    'avatar' => 'Foto',
    'password' => 'Palavra-passe',
    'update_password' => 'Atualizar Palavra-passe',
    'current_password' => 'Palavra-passe Atual',
    'new_password' => 'Nova Palavra-passe',
    'confirm_password' => 'Confirmar Palavra-passe',
    'ensure_your_password' => 'Garanta que a sua conta está a usar uma palavra-passe longa e aleatória para manter a segurança.',
    'delete_account' => 'Eliminar Conta',
    'delete_account_description' => 'Eliminar permanentemente a sua conta.',
    'yes_delete_it' => 'Sim, eliminar!',
    'are_you_sure' => 'Tem a certeza de que deseja eliminar a sua conta? Esta ação não pode ser desfeita!',
    'incorrect_password' => 'A palavra-passe que introduziu está incorreta. Por favor, tente novamente.',
    'user_load_error' => 'O objeto de utilizador autenticado deve ser um modelo Eloquent para permitir que a página do perfil o atualize.',
    'delete_account_card_description' => 'Uma vez eliminada a sua conta, todos os seus recursos e dados serão permanentemente apagados. Antes de eliminar a sua conta, por favor, descarregue quaisquer dados ou informações que deseje manter.',
    'saved_successfully' => 'As informações do seu perfil foram guardadas com sucesso.',
    'custom_fields' => 'Campos Personalizados',
    'custom_fields_description' => 'Atualize os seus campos personalizados.',
    'save' => 'Guardar',
    'token_name' => 'Nome do Token',
    'token_abilities' => 'Habilidades',
    'token_created_at' => 'Criado em',
    'token_expires_at' => 'Expira em',
    'token_section_title' => 'Tokens de API',
    'token_section_description' => 'Gerir tokens de API que permitem que serviços de terceiros acedam a esta aplicação em seu nome.',
    'token_action_label' => 'Criar Token',
    'token_modal_heading' => 'Criar',
    'token_create_notification' => 'Token criado com sucesso!',
    'token_helper_text' => 'O seu token é mostrado apenas uma vez aquando da criação. Se perder o seu token, terá de o eliminar e criar um novo.',
    'token_modal_heading_2' => 'Copiar Token de Acesso Pessoal',
    'token_empty_state_heading' => 'Crie o seu primeiro token',
    'token_empty_state_description' => 'Crie um token de acesso pessoal para começar.',
    'browser_section_title' => 'Sessões do Navegador',
    'browser_section_description' => 'Gerir e terminar as suas sessões ativas em outros navegadores e dispositivos.',
    'browser_sessions_content' => 'Se necessário, pode terminar todas as suas outras sessões de navegador em todos os seus dispositivos. Algumas das suas sessões recentes estão listadas abaixo; no entanto, esta lista pode não ser exaustiva. Se sentir que a sua conta foi comprometida, deve também atualizar a sua palavra-passe.',
    'browser_sessions_device' => 'Este dispositivo',
    'browser_sessions_last_active' => 'Última atividade',
    'browser_sessions_log_out' => 'Terminar Sessões de Outros Navegadores',
    'browser_sessions_confirm_pass' => 'Por favor, introduza a sua palavra-passe para confirmar que deseja terminar as sessões de outros navegadores em todos os seus dispositivos.',
    'browser_sessions_logout_success_notification' => 'Todas as outras sessões do navegador foram desconectadas com êxito.',
    'two_factor' => [
        'heading'                      => 'Autenticação de Dois Fatores',
        'description'                  => 'Adicione segurança extra à sua conta usando a autenticação de dois fatores',
        'password_incorrect'           => 'O :attribute fornecido está incorreto.',
        'notification_title_approved'  => 'A autenticação de dois fatores foi aprovada!',
        'code'                         => 'Código',
        'recovery_code'                => 'Código de recuperação',
        'use_recovery_code'            => 'Usar código de recuperação',
        'you_can_logout'               => 'ou pode sair',
        'button_confirm'               => 'Confirmar',
        'code_incorrect'               => 'O código está incorreto!',
        'enabled' => [
            'heading'                    => 'Ativar autenticação de dois fatores',
            'description'                => 'A autenticação de dois fatores adiciona uma camada extra de segurança à sua conta. Quando ativada, será solicitado um token seguro e aleatório durante a autenticação.',
            'sub_description'            => 'Para ativar a autenticação de dois fatores, confirme a sua palavra-passe abaixo.',
            'label'                      => 'Ativar autenticação de dois fatores',
            'modal_heading'              => 'Ativar autenticação de dois fatores',
            'modal_description'          => 'Tem a certeza de que deseja ativar a autenticação de dois fatores?',
            'modal_submit_action_label'  => 'Ativar',
            'notification_title'         => 'A autenticação de dois fatores foi ativada',
        ],
        'disabled' => [
            'heading'                       => 'Ativou a autenticação de dois fatores.',
            'description'                   => 'Quando a autenticação de dois fatores estiver ativada, será solicitado um token seguro e aleatório durante a autenticação.',
            'sub_description'               => 'Para concluir a ativação da autenticação de dois fatores, digitalize o código QR abaixo utilizando a aplicação autenticadora no seu telefone ou introduza a chave de configuração e forneça o código OTP gerado.',
            'setup_key'                     => 'Chave de configuração:',
            'recovery_code_description'     => 'Armazene estes códigos de recuperação num gestor de palavras-passe seguro. Estes códigos podem ser utilizados para recuperar o acesso à sua conta se perder o dispositivo de autenticação de dois fatores.',
            'label'                         => 'Desativar autenticação de dois fatores',
            'modal_heading'                 => 'Desativar autenticação de dois fatores',
            'modal_description'             => 'Tem a certeza de que deseja desativar a autenticação de dois fatores?',
            'modal_submit_action_label'     => 'Desativar',
            'notification_title'            => 'A autenticação de dois fatores foi desativada',
        ],
    ],
];
