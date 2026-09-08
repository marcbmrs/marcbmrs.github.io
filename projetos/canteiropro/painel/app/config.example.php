<?php
declare(strict_types=1);

// Copie este arquivo como config.local.php e preencha apenas no servidor.
// Mantenha diretorio_fotos_obras fora de public_html para proteger fotos dos clientes.
return [
    'host' => 'localhost',
    'database' => 'NOME_DO_BANCO',
    'username' => 'USUARIO_DO_BANCO',
    'password' => 'SENHA_FORTE_DO_BANCO',
    'diretorio_fotos_obras' => '/home/USUARIO_HOSTINGER/canteiropro-private/obras',
];

