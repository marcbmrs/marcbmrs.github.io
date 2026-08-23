<?php
declare(strict_types=1);

// Este script só pode ser executado localmente pela linha de comando.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require dirname(__DIR__) . '/app/bootstrap.php';
require dirname(__DIR__) . '/app/database.php';

function ask(string $label): string
{
    echo $label;
    return trim((string) fgets(STDIN));
}

$name = ask('Nome do administrador: ');
$email = strtolower(ask('E-mail do administrador: '));
$password = ask('Senha (mínimo 12 caracteres): ');

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
    fwrite(STDERR, "Dados inválidos. Use e-mail válido e senha com ao menos 12 caracteres.\n");
    exit(1);
}

try {
    $statement = database()->prepare(
        'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
    );
    $statement->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    echo "Administrador criado com sucesso.\n";
} catch (PDOException $exception) {
    fwrite(STDERR, "Não foi possível criar o administrador. Verifique se o e-mail já está cadastrado.\n");
    exit(1);
}
