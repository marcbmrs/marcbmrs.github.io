<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

if (PHP_SAPI !== 'cli' || $argc < 4) {
    exit("Uso: php criar-usuario-inicial.php \"Nome\" email@empresa.com \"senha-segura\"\n");
}
[$arquivo, $nome, $email, $senha] = $argv;
$email = mb_strtolower(trim($email));
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) exit("Informe um e-mail válido e senha de pelo menos 8 caracteres.\n");
$comando = banco()->prepare('INSERT INTO usuarios_equipe (nome, email, senha_hash) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nome = VALUES(nome), senha_hash = VALUES(senha_hash), ativo = 1');
$comando->execute([trim($nome), $email, password_hash($senha, PASSWORD_DEFAULT)]);
echo "Usuário inicial salvo.\n";
