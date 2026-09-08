<?php
declare(strict_types=1);
require __DIR__ . '/../app/db.php';

$pdo = banco();
$pdo->beginTransaction();
try {
    $codigo = 'ORC-DEMO-005';
    $busca = $pdo->prepare('SELECT id FROM orcamentos WHERE codigo = ?');
    $busca->execute([$codigo]);
    if ($idAnterior = $busca->fetchColumn()) {
        $pdo->prepare('DELETE FROM obras_acompanhamento WHERE orcamento_id = ?')->execute([$idAnterior]);
        $pdo->prepare('DELETE FROM orcamentos WHERE id = ?')->execute([$idAnterior]);
    }

    $cliente = $pdo->prepare('INSERT INTO clientes (nome, telefone) VALUES (?, ?)');
    $cliente->execute(['Juliana Ribeiro', '5551998887766']);
    $clienteId = (int) $pdo->lastInsertId();
    $orcamento = $pdo->prepare("INSERT INTO orcamentos (codigo, cliente_id, local_obra, cidade, bairro, endereco, complemento, cep, validade, prazo_dias, status, revisao, total_centavos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pronto_para_envio', 1, ?)");
    $orcamento->execute([$codigo, $clienteId, 'Rua das Oliveiras, 240', 'Canoas', 'Nossa Senhora das Graças', 'Rua das Oliveiras, 240', 'Casa 02', '92025-410', '7 dias', 16, 986000]);
    $orcamentoId = (int) $pdo->lastInsertId();
    $item = $pdo->prepare('INSERT INTO itens_orcamento (orcamento_id, categoria, descricao, unidade, quantidade, periodo_quantidade, periodo_tipo, valor_tabela_centavos, valor_unitario_centavos, subtotal_centavos) VALUES (?, ?, ?, ?, ?, NULL, NULL, ?, ?, ?)');
    $item->execute([$orcamentoId, 'mao_de_obra', 'Assentamento de revestimento de cozinha', 'm²', 22, 24500, 24500, 539000]);
    $item->execute([$orcamentoId, 'mao_de_obra', 'Pintura de paredes e teto', 'm²', 38, 8500, 8500, 323000]);
    $item->execute([$orcamentoId, 'mao_de_obra', 'Acabamentos e limpeza final', 'serviço', 1, 124000, 124000, 124000]);

    $token = hash('sha256', 'demo-juliana-ribeiro-cozinha-2026');
    $obra = $pdo->prepare("INSERT INTO obras_acompanhamento (orcamento_id, titulo, cliente_nome, localidade, status, proxima_etapa, token_acesso, codigo_acesso_hash, codigo_acesso_exibicao) VALUES (?, ?, ?, ?, 'concluida', ?, ?, ?, ?)");
    $obra->execute([$orcamentoId, 'Reforma de cozinha', 'Juliana Ribeiro', 'Canoas/RS · Nossa Senhora das Graças', 'Obra entregue e finalizada', $token, password_hash('736204', PASSWORD_DEFAULT), '736204']);
    $obraId = (int) $pdo->lastInsertId();
    $atualizacao = $pdo->prepare('INSERT INTO atualizacoes_obra (obra_id, data_atualizacao, status, resumo, proximo_passo) VALUES (?, ?, ?, ?, ?)');
    $foto = $pdo->prepare('INSERT INTO fotos_atualizacao_obra (atualizacao_id, etapa, arquivo, descricao) VALUES (?, ?, ?, ?)');
    $historico = [
        ['2026-07-03', 'em_andamento', 'Protegemos o ambiente, organizamos os materiais e iniciamos a preparação da cozinha.', 'Remover os revestimentos antigos.'],
        ['2026-07-05', 'em_andamento', 'Retiramos os revestimentos antigos e regularizamos os pontos necessários da parede.', 'Conferir o prumo e preparar a base.'],
        ['2026-07-07', 'em_andamento', 'A base foi preparada e deixamos o ambiente pronto para receber o novo revestimento.', 'Iniciar o assentamento da parede principal.'],
        ['2026-07-09', 'em_andamento', 'Começamos o assentamento do revestimento, mantendo o alinhamento e os espaçamentos definidos.', 'Avançar nas demais paredes.'],
        ['2026-07-11', 'em_andamento', 'Concluímos o revestimento das paredes e iniciamos os acabamentos próximos à bancada.', 'Fazer o rejuntamento e revisar os encontros.'],
        ['2026-07-14', 'em_andamento', 'Finalizamos o rejuntamento e avançamos com a pintura do teto e das paredes complementares.', 'Concluir pintura e limpeza detalhada.'],
        ['2026-07-17', 'em_andamento', 'A pintura foi concluída e realizamos a limpeza técnica do ambiente para a entrega.', 'Conferência final com a cliente.'],
        ['2026-07-19', 'concluida', 'Reforma concluída e entregue. A cozinha recebeu novo revestimento, pintura e acabamento final.', 'Obra entregue e finalizada.'],
    ];
    foreach ($historico as $indice => [$data, $status, $resumo, $proximo]) {
        $atualizacao->execute([$obraId, $data, $status, $resumo, $proximo]);
        $atualizacaoId = (int) $pdo->lastInsertId();
        if ($indice === 0) $foto->execute([$atualizacaoId, 'antes', 'demo-juliana-cozinha-antes-20260829.png', 'Cozinha antes do início da reforma']);
        if ($indice === 3 || $indice === 5) $foto->execute([$atualizacaoId, 'durante', 'demo-juliana-cozinha-antes-20260829.png', 'Andamento da preparação e do revestimento']);
        if ($indice === 7) $foto->execute([$atualizacaoId, 'depois', 'demo-juliana-cozinha-depois-20260829.png', 'Cozinha concluída e pronta para uso']);
    }
    $pdo->commit();
    echo "Demonstração concluída criada: ORC-DEMO-005, obra com 8 atualizações. Código do cliente: 736204\n";
} catch (Throwable $erro) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $erro;
}

