<?php

declare(strict_types=1);

/**
 * Catálogo inicial do painel de orçamentos.
 *
 * Os valores ficam em centavos para evitar imprecisão de ponto flutuante.
 * Quando o painel estiver conectado ao banco de dados, estes dados deverão ser
 * usados apenas para a carga inicial. Cada orçamento deverá guardar sua própria
 * cópia do nome, unidade e valor dos itens selecionados.
 */
return [
    'equipamentos' => [
        ['nome' => 'Betoneira 400 L', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 9000]]],
        ['nome' => 'Martelete 2 kg', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 6000]]],
        ['nome' => 'Martelete 5 kg', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 8000]]],
        ['nome' => 'Rompedor 15 kg', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 12000]]],
        ['nome' => 'Rompedor 30 kg', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 13000]]],
        ['nome' => 'Compactador de solo', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 22000]]],
        ['nome' => 'Placa vibratória', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 20000]]],
        ['nome' => 'Andaime tubular', 'tarifas' => [['tipo' => 'peca', 'rotulo' => 'Por peça', 'valor_centavos' => 1000]]],
        ['nome' => 'Escada profissional', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 6000]]],
        ['nome' => 'Furadeira de impacto', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 5000]]],
        ['nome' => 'Esmerilhadeira', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 5000]]],
        ['nome' => 'Serra circular', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 7000]]],
        ['nome' => 'Cortadora de piso', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 12000]]],
        ['nome' => 'Vibrador de concreto', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 10000]]],
        ['nome' => 'Gerador', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 20000]]],
        ['nome' => 'Lavadora de alta pressão', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 10000]]],
        ['nome' => 'Lixadeira de parede (girafa)', 'tarifas' => [['tipo' => 'diaria', 'rotulo' => 'Diária', 'valor_centavos' => 12000]]],
    ],
    'servicos_mao_de_obra' => [
        ['nome' => 'Alvenaria com tijolo/bloco', 'unidade' => 'm²', 'valor_centavos' => 10000],
        ['nome' => 'Construção de parede', 'unidade' => 'm²', 'valor_centavos' => 10000],
        ['nome' => 'Construção de muro', 'unidade' => 'm²', 'valor_centavos' => 15000],
        ['nome' => 'Levantamento de cômodo', 'unidade' => 'm²', 'valor_centavos' => 12000],
        ['nome' => 'Chapisco', 'unidade' => 'm²', 'valor_centavos' => 3000],
        ['nome' => 'Emboço', 'unidade' => 'm²', 'valor_centavos' => 5500],
        ['nome' => 'Reboco', 'unidade' => 'm²', 'valor_centavos' => 6500],
        ['nome' => 'Chapisco + reboco', 'unidade' => 'm²', 'valor_centavos' => 8000],
        ['nome' => 'Reboco interno', 'unidade' => 'm²', 'valor_centavos' => 6000],
        ['nome' => 'Reboco externo', 'unidade' => 'm²', 'valor_centavos' => 7000],
        ['nome' => 'Contrapiso', 'unidade' => 'm²', 'valor_centavos' => 5000],
        ['nome' => 'Regularização de piso', 'unidade' => 'm²', 'valor_centavos' => 4500],
        ['nome' => 'Assentamento de piso cerâmico', 'unidade' => 'm²', 'valor_centavos' => 6000],
        ['nome' => 'Assentamento de porcelanato', 'unidade' => 'm²', 'valor_centavos' => 9500],
        ['nome' => 'Porcelanato grande formato', 'unidade' => 'm²', 'valor_centavos' => 18000],
        ['nome' => 'Piso sobre piso', 'unidade' => 'm²', 'valor_centavos' => 12000],
        ['nome' => 'Azulejo/revestimento de parede', 'unidade' => 'm²', 'valor_centavos' => 9000],
        ['nome' => 'Revestimento de banheiro', 'unidade' => 'm²', 'valor_centavos' => 10000],
        ['nome' => 'Rejuntamento', 'unidade' => 'm²', 'valor_centavos' => 3000],
        ['nome' => 'Rodapé comum', 'unidade' => 'metro linear', 'valor_centavos' => 2500],
        ['nome' => 'Rodapé embutido', 'unidade' => 'metro linear', 'valor_centavos' => 6000],
        ['nome' => 'Soleira', 'unidade' => 'unidade', 'valor_centavos' => 8000],
        ['nome' => 'Peitoril', 'unidade' => 'metro linear', 'valor_centavos' => 7000],
        ['nome' => 'Impermeabilização', 'unidade' => 'm²', 'valor_centavos' => 12000],
        ['nome' => 'Impermeabilização de banheiro', 'unidade' => 'm²', 'valor_centavos' => 13000],
        ['nome' => 'Construção de calçada', 'unidade' => 'm²', 'valor_centavos' => 8000],
        ['nome' => 'Concretagem de piso', 'unidade' => 'm²', 'valor_centavos' => 7000],
        ['nome' => 'Piso de concreto', 'unidade' => 'm²', 'valor_centavos' => 8000],
        ['nome' => 'Escada de alvenaria', 'unidade' => 'degrau', 'valor_centavos' => 15000],
        ['nome' => 'Vergas/contravergas', 'unidade' => 'metro', 'valor_centavos' => 7000],
        ['nome' => 'Fechamento de vão', 'unidade' => 'm²', 'valor_centavos' => 10000],
        ['nome' => 'Abertura de vão em parede', 'unidade' => 'm²', 'valor_centavos' => 15000],
        ['nome' => 'Demolição de parede', 'unidade' => 'm²', 'valor_centavos' => 7000],
        ['nome' => 'Demolição de piso', 'unidade' => 'm²', 'valor_centavos' => 5000],
        ['nome' => 'Retirada de revestimento', 'unidade' => 'm²', 'valor_centavos' => 5000],
        ['nome' => 'Retirada de reboco', 'unidade' => 'm²', 'valor_centavos' => 4500],
        ['nome' => 'Construção de churrasqueira', 'unidade' => 'unidade', 'valor_minimo_centavos' => 150000, 'valor_maximo_centavos' => 300000],
        ['nome' => 'Construção de forno a lenha', 'unidade' => 'unidade', 'valor_minimo_centavos' => 150000, 'valor_maximo_centavos' => 300000],
        ['nome' => 'Construção de bancada de alvenaria', 'unidade' => 'metro', 'valor_centavos' => 25000],
        ['nome' => 'Construção de nicho de banheiro', 'unidade' => 'unidade', 'valor_centavos' => 25000],
        ['nome' => 'Fechamento de rasgos de elétrica', 'unidade' => 'metro', 'valor_centavos' => 3000],
        ['nome' => 'Fechamento de rasgos hidráulicos', 'unidade' => 'metro', 'valor_centavos' => 4000],
        ['nome' => 'Pequenos reparos de alvenaria', 'unidade' => 'a confirmar', 'valor_centavos' => 4000],
        ['nome' => 'Telhado', 'unidade' => 'a confirmar', 'valor_centavos' => 3500],
        ['nome' => 'Solda simples', 'unidade' => 'serviço', 'valor_minimo_centavos' => 8000, 'valor_maximo_centavos' => 15000],
        ['nome' => 'Solda média', 'unidade' => 'serviço', 'valor_minimo_centavos' => 15000, 'valor_maximo_centavos' => 30000],
        ['nome' => 'Solda de grande complexidade', 'unidade' => 'serviço', 'valor_minimo_centavos' => 30000, 'valor_maximo_centavos' => 60000],
        ['nome' => 'Marco de porta', 'unidade' => 'unidade', 'valor_centavos' => 20000],
        ['nome' => 'Porta simples', 'unidade' => 'unidade', 'valor_centavos' => 15000],
        ['nome' => 'Retirada e instalação de porta', 'unidade' => 'unidade', 'valor_centavos' => 35000],
        ['nome' => 'Arremate do oitão', 'unidade' => 'serviço', 'valor_centavos' => 30000],
    ],
];

