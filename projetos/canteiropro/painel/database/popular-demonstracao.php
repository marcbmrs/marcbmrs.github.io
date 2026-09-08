<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

$demonstracoes = [
    ['ORC-DEMO-001', 'Ana Martins', '(51) 99911-2233', 'Canoas', '7 dias', 'aguardando_cliente', 485000],
    ['ORC-DEMO-002', 'Carlos Schneider', '(51) 99822-4455', 'Porto Alegre', '10 dias', 'negociacao', 1275000],
    ['ORC-DEMO-003', 'Fernanda Lopes', '(51) 99733-6677', 'Gravataí', '7 dias', 'aprovado', 842000],
    ['ORC-DEMO-004', 'João da Silva', '(51) 99644-8899', 'Viamão', '5 dias', 'rascunho', 369000],
    ['ORC-DEMO-005', 'Mariana Costa', '(51) 99555-1122', 'Alvorada', '10 dias', 'aguardando_cliente', 2140000],
    ['ORC-DEMO-006', 'Pedro Almeida', '(51) 99466-3344', 'Esteio', '7 dias', 'recusado', 598000],
    ['ORC-DEMO-007', 'Rita de Oliveira', '(51) 99377-5566', 'Sapucaia do Sul', '15 dias', 'aprovado', 1810000],
    ['ORC-DEMO-008', 'Lucas Pereira', '(51) 99288-7788', 'Novo Hamburgo', '7 dias', 'rascunho', 976000],
];

$pdo = banco();
$clienteStmt = $pdo->prepare('INSERT INTO clientes (nome, telefone) VALUES (?, ?)');
$orcamentoStmt = $pdo->prepare('INSERT INTO orcamentos (codigo, cliente_id, local_obra, validade, status, total_centavos) VALUES (?, ?, ?, ?, ?, ?)');
$pdo->beginTransaction();
foreach ($demonstracoes as [$codigo, $nome, $telefone, $local, $validade, $status, $total]) {
    $existe = $pdo->prepare('SELECT 1 FROM orcamentos WHERE codigo = ?');
    $existe->execute([$codigo]);
    if ($existe->fetchColumn()) continue;
    $clienteStmt->execute([$nome, $telefone]);
    $orcamentoStmt->execute([$codigo, $pdo->lastInsertId(), $local, $validade, $status, $total]);
}
$pdo->commit();
echo "Dados de demonstração inseridos.";

