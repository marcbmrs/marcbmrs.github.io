<?php
declare(strict_types=1);

function moedaContrato(int $centavos): string { return 'R$ ' . number_format($centavos / 100, 2, ',', '.'); }

function montarMinutaContrato(array $orcamento): string
{
    $consulta = banco()->prepare('SELECT valor FROM configuracoes_contrato WHERE chave = ?');
    $consulta->execute(['modelo_base']);
    $modelo = $consulta->fetchColumn() ?: file_get_contents(__DIR__ . '/../contrato-modelo.md');
    $inicio = strpos($modelo, '# Contrato particular');
    $modelo = $inicio === false ? $modelo : substr($modelo, $inicio);
    $prazo = (int) ($orcamento['prazo_dias'] ?? 0);
    $total = (int) $orcamento['total_centavos'];
    $entrada = (int) round($total * .30);
    $meio = $prazo > 20 ? (int) round($total * .10) : 0;
    $final = $total - $entrada - $meio;
    $pagamento = $prazo > 20
        ? '30% no início (' . moedaContrato($entrada) . '), 10% no 20º dia (' . moedaContrato($meio) . ') e 60% na conclusão (' . moedaContrato($final) . ').'
        : '30% no início (' . moedaContrato($entrada) . ') e 70% na conclusão (' . moedaContrato($final) . ').';
    $endereco = trim(implode(', ', array_filter([$orcamento['endereco'] ?? '', $orcamento['complemento'] ?? '', $orcamento['bairro'] ?? '', $orcamento['cidade'] ?? '', $orcamento['cep'] ?? ''])));
    $modelo = str_replace(
        '**Contratante:** [nome completo], CPF/CNPJ [número], endereço [endereço], telefone [telefone].',
        '**Contratante:** ' . $orcamento['nome'] . ', CPF/CNPJ [a informar], endereço ' . ($endereco ?: '[a informar]') . ', telefone ' . ($orcamento['telefone'] ?: '[a informar]') . '.',
        $modelo
    );
    $modelo = str_replace(
        '**Contratada:** [nome do prestador], representada por [nome completo], CPF/CNPJ [número], endereço [endereço], telefone [telefone].',
        '**Contratada:** [nome do prestador], representada por [responsável a informar], CPF/CNPJ [a informar], endereço [a informar], telefone [a informar].',
        $modelo
    );
    return str_replace(
        ['[código]', '[valor]', '[data]', '[prazo]', '[cidade]', '[responsável]', '[nome]'],
        [$orcamento['codigo'], moedaContrato($total) . '. ' . $pagamento, '[data de início a confirmar]', $prazo ? $prazo . ' dias' : '[prazo a confirmar]', $orcamento['cidade'] ?: '[cidade a informar]', '[responsável do prestador]', $orcamento['nome']],
        $modelo
    );
}

