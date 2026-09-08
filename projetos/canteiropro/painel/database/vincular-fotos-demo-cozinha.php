<?php
declare(strict_types=1);

require __DIR__ . '/../app/db.php';

$obra = banco()->prepare('SELECT id FROM obras_acompanhamento WHERE titulo = ? LIMIT 1');
$obra->execute(['Reforma de cozinha']);
$obraId = (int) $obra->fetchColumn();
if ($obraId === 0) {
    exit("Obra 'Reforma de cozinha' não encontrada.\n");
}

$atualizacoes = banco()->prepare('SELECT id, data_atualizacao FROM atualizacoes_obra WHERE obra_id = ? ORDER BY data_atualizacao');
$atualizacoes->execute([$obraId]);
$porData = [];
foreach ($atualizacoes as $atualizacao) {
    $porData[(string) $atualizacao['data_atualizacao']] = (int) $atualizacao['id'];
}

$fotosPorData = [
    '2026-07-03' => [
        ['antes', 'demo-juliana-cozinha-antes-20260829.png', 'Cozinha antes do início da reforma'],
        ['durante', 'demo-cozinha-01-protecao.png', 'Ambiente protegido e materiais organizados'],
    ],
    '2026-07-05' => [
        ['durante', 'demo-cozinha-01-protecao.png', 'Proteção do ambiente antes da remoção'],
        ['durante', 'demo-cozinha-02-remocao.png', 'Remoção dos revestimentos antigos'],
    ],
    '2026-07-07' => [
        ['durante', 'demo-cozinha-02-remocao.png', 'Parede após a retirada do revestimento'],
        ['durante', 'demo-cozinha-03-preparo.png', 'Base preparada para receber o novo acabamento'],
    ],
    '2026-07-09' => [
        ['durante', 'demo-cozinha-03-preparo.png', 'Preparação concluída antes do assentamento'],
        ['durante', 'demo-cozinha-04-revestimento.png', 'Início do assentamento do revestimento'],
    ],
    '2026-07-11' => [
        ['durante', 'demo-cozinha-04-revestimento.png', 'Revestimento instalado com espaçadores e nivelamento'],
        ['durante', 'demo-cozinha-05-rejuntamento.png', 'Acabamentos e rejuntamento em andamento'],
    ],
    '2026-07-14' => [
        ['durante', 'demo-cozinha-05-rejuntamento.png', 'Rejuntamento e acabamentos concluídos'],
        ['durante', 'demo-cozinha-06-limpeza.png', 'Pintura e limpeza técnica do ambiente'],
    ],
    '2026-07-17' => [
        ['durante', 'demo-cozinha-06-limpeza.png', 'Limpeza técnica antes da entrega'],
        ['depois', 'demo-juliana-cozinha-depois-20260829.png', 'Cozinha pronta após os acabamentos'],
    ],
    '2026-07-19' => [
        ['depois', 'demo-juliana-cozinha-depois-20260829.png', 'Cozinha concluída e pronta para uso'],
        ['depois', 'demo-cozinha-06-limpeza.png', 'Ambiente limpo e preparado para a entrega'],
    ],
];

if (array_diff(array_keys($fotosPorData), array_keys($porData)) !== []) {
    exit("As atualizações esperadas da demonstração não foram encontradas.\n");
}

$excluir = banco()->prepare('DELETE FROM fotos_atualizacao_obra WHERE atualizacao_id = ?');
$inserir = banco()->prepare('INSERT INTO fotos_atualizacao_obra (atualizacao_id, etapa, arquivo, descricao) VALUES (?, ?, ?, ?)');

banco()->beginTransaction();
try {
    foreach ($fotosPorData as $data => $fotos) {
        $atualizacaoId = $porData[$data];
        $excluir->execute([$atualizacaoId]);
        foreach ($fotos as [$etapa, $arquivo, $descricao]) {
            $inserir->execute([$atualizacaoId, $etapa, $arquivo, $descricao]);
        }
    }
    banco()->commit();
    echo "16 fotos vinculadas às 8 atualizações da Reforma de cozinha.\n";
} catch (Throwable $erro) {
    if (banco()->inTransaction()) {
        banco()->rollBack();
    }
    throw $erro;
}

