<?php
declare(strict_types=1);

require __DIR__ . '/../app/db.php';

$pdo = banco();
try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (['fotos_atualizacao_obra', 'atualizacoes_obra', 'obras_acompanhamento', 'itens_orcamento', 'minutas_contrato', 'orcamentos', 'clientes', 'configuracoes_contrato'] as $tabela) {
        $pdo->exec('TRUNCATE TABLE ' . $tabela);
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    $pdo->beginTransaction();

    $cliente = $pdo->prepare('INSERT INTO clientes (nome, telefone) VALUES (?, ?)');
    $orcamento = $pdo->prepare('INSERT INTO orcamentos (codigo, cliente_id, local_obra, cidade, bairro, endereco, validade, prazo_dias, status, total_centavos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $item = $pdo->prepare('INSERT INTO itens_orcamento (orcamento_id, categoria, descricao, unidade, quantidade, periodo_quantidade, periodo_tipo, valor_tabela_centavos, valor_unitario_centavos, subtotal_centavos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $dados = [
        ['ORC-DEMO-001', 'Ana Carolina', '555199112233', 'Canoas', 'Niterói', 'Rua das Acácias, 120', 'rascunho', 12, [['Chapisco + reboco', 'm²', 25, 8000], ['Pintura interna', 'm²', 25, 2200]]],
        ['ORC-DEMO-002', 'Bruno Martins', '555199223344', 'Porto Alegre', 'Tristeza', 'Rua da Pedra, 85', 'pronto_para_envio', 8, [['Construção de parede', 'm²', 18, 10000], ['Reboco interno', 'm²', 18, 6000]]],
        ['ORC-DEMO-003', 'Clara Souza', '555199334455', 'Viamão', 'Santa Isabel', 'Estrada do Sol, 410', 'aguardando_cliente', 18, [['Impermeabilização de banheiro', 'm²', 12, 13000], ['Assentamento de piso cerâmico', 'm²', 20, 6000]]],
        ['ORC-DEMO-004', 'Fernanda Lopes', '555199445566', 'Gravataí', 'Centro', 'Rua das Palmeiras, 52', 'aprovado', 24, [['Demolição de revestimento', 'm²', 18, 5000], ['Impermeabilização de banheiro', 'm²', 14, 13000], ['Assentamento de porcelanato', 'm²', 28, 9500]]],
    ];
    $ids = [];
    foreach ($dados as [$codigo, $nome, $telefone, $cidade, $bairro, $endereco, $status, $prazo, $itens]) {
        $cliente->execute([$nome, $telefone]);
        $clienteId = (int) $pdo->lastInsertId();
        $total = 0;
        foreach ($itens as [, , $quantidade, $valor]) $total += $quantidade * $valor;
        $orcamento->execute([$codigo, $clienteId, $cidade . ' · ' . $bairro, $cidade, $bairro, $endereco, '7 dias', $prazo, $status, $total]);
        $orcamentoId = (int) $pdo->lastInsertId();
        $ids[$status] = $orcamentoId;
        foreach ($itens as [$descricao, $unidade, $quantidade, $valor]) {
            $item->execute([$orcamentoId, 'mao_de_obra', $descricao, $unidade, $quantidade, null, null, $valor, $valor, $quantidade * $valor]);
        }
    }

    $token = hash('sha256', 'demo-fernanda-lopes-obra-2026');
    $obra = $pdo->prepare('INSERT INTO obras_acompanhamento (orcamento_id, titulo, cliente_nome, localidade, status, proxima_etapa, token_acesso, codigo_acesso_hash, codigo_acesso_exibicao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $obra->execute([$ids['aprovado'], 'Reforma de banheiro', 'Fernanda Lopes', 'Gravataí · Centro', 'em_andamento', 'Concluir o assentamento do revestimento e iniciar o rejuntamento.', $token, password_hash('184116', PASSWORD_DEFAULT), '184116']);
    $obraId = (int) $pdo->lastInsertId();
    $atualizacao = $pdo->prepare('INSERT INTO atualizacoes_obra (obra_id, data_atualizacao, status, resumo, proximo_passo) VALUES (?, ?, ?, ?, ?)');
    $foto = $pdo->prepare('INSERT INTO fotos_atualizacao_obra (atualizacao_id, etapa, arquivo, descricao) VALUES (?, ?, ?, ?)');
    $atualizacoes = [
        ['2026-08-25', 'em_andamento', 'Protegemos o ambiente, retiramos o revestimento antigo e fizemos a limpeza inicial do banheiro.', 'Preparar as paredes para a impermeabilização.', [['antes', 'demo-fernanda-antes-20260829.png', 'Ambiente antes do início da reforma']]],
        ['2026-08-27', 'em_andamento', 'Concluímos a impermeabilização e corrigimos os pontos necessários antes do novo revestimento.', 'Iniciar o assentamento do revestimento.', [['durante', 'demo-fernanda-durante-20260829.png', 'Impermeabilização e preparação concluídas']]],
        ['2026-08-29', 'em_andamento', 'Iniciamos o assentamento do novo revestimento e mantivemos o ambiente organizado para a próxima etapa.', 'Concluir o assentamento do revestimento e iniciar o rejuntamento.', []],
    ];
    foreach ($atualizacoes as [$data, $status, $resumo, $proximo, $fotos]) {
        $atualizacao->execute([$obraId, $data, $status, $resumo, $proximo]);
        $atualizacaoId = (int) $pdo->lastInsertId();
        foreach ($fotos as [$etapa, $arquivo, $descricao]) $foto->execute([$atualizacaoId, $etapa, $arquivo, $descricao]);
    }
    $pdo->commit();
    echo "Demonstração criada: 4 orçamentos e 1 obra com 3 atualizações.\n";
} catch (Throwable $erro) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    throw $erro;
}
