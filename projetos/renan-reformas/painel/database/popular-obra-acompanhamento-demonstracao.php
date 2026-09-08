<?php
declare(strict_types=1);

/** Cria uma obra fictícia para avaliar a experiência do cliente. */
require __DIR__ . '/../app/db.php';

try {
    $pdo = banco();
    $token = hash('sha256', 'demonstracao-reforma-banheiro-renan-2026');
    $consulta = $pdo->prepare('SELECT id FROM obras_acompanhamento WHERE token_acesso = ?');
    $consulta->execute([$token]);
    if ($consulta->fetchColumn()) {
        echo "A obra demonstrativa já existe.\n";
        exit;
    }
    $pdo->beginTransaction();
    $obra = $pdo->prepare('INSERT INTO obras_acompanhamento (titulo, cliente_nome, localidade, status, proxima_etapa, token_acesso, codigo_acesso_hash) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $obra->execute(['Reforma de banheiro', 'Cliente demonstração', 'Viamão/RS', 'em_andamento', 'Finalizar o revestimento da parede principal.', $token, password_hash('482913', PASSWORD_DEFAULT)]);
    $obraId = (int) $pdo->lastInsertId();
    $atualizacao = $pdo->prepare('INSERT INTO atualizacoes_obra (obra_id, data_atualizacao, status, resumo, proximo_passo) VALUES (?, ?, ?, ?, ?)');
    $foto = $pdo->prepare('INSERT INTO fotos_atualizacao_obra (atualizacao_id, etapa, descricao) VALUES (?, ?, ?)');
    $dados = [
        ['2026-08-25', 'em_andamento', 'Iniciamos a obra com a proteção do ambiente e a retirada do revestimento antigo.', 'Preparar as paredes para a impermeabilização.', [['antes', 'Ambiente antes da reforma'], ['durante', 'Proteção do ambiente']]],
        ['2026-08-27', 'em_andamento', 'A remoção foi concluída e corrigimos os pontos necessários antes da nova etapa.', 'Aplicar a impermeabilização.', [['durante', 'Preparação das paredes']]],
        ['2026-08-28', 'em_andamento', 'Concluímos a impermeabilização e iniciamos o assentamento do novo revestimento.', 'Finalizar o revestimento da parede principal.', [['durante', 'Impermeabilização concluída'], ['durante', 'Início do revestimento']]],
    ];
    foreach ($dados as [$data, $status, $resumo, $proximo, $fotos]) {
        $atualizacao->execute([$obraId, $data, $status, $resumo, $proximo]);
        $atualizacaoId = (int) $pdo->lastInsertId();
        foreach ($fotos as [$etapa, $descricao]) $foto->execute([$atualizacaoId, $etapa, $descricao]);
    }
    $pdo->commit();
    echo "Obra demonstrativa criada. Código de acesso: 482913\n";
} catch (Throwable $erro) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    fwrite(STDERR, $erro->getMessage() . "\n");
    exit(1);
}
