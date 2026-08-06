$ErrorActionPreference = 'Stop'

$root = 'C:\marcbmrs.github.io'
$brandBibleRoot = Join-Path $root 'BrandBible'
$legacyRoot = Join-Path $brandBibleRoot 'legacy'

function Write-Utf8File {
    param(
        [string]$Path,
        [string]$Text
    )

    $dir = Split-Path -Parent $Path
    if ($dir -and -not (Test-Path -LiteralPath $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }

    [System.IO.File]::WriteAllText($Path, $Text + "`r`n", [System.Text.UTF8Encoding]::new($false))
}

function Read-Docs {
    param(
        [string[]]$RelativePaths
    )

    $parts = @()
    foreach ($relativePath in $RelativePaths) {
        $fullPath = Join-Path $brandBibleRoot $relativePath
        if (-not (Test-Path -LiteralPath $fullPath)) {
            throw "Arquivo não encontrado: $fullPath"
        }
        $parts += (Get-Content -LiteralPath $fullPath -Raw -Encoding UTF8).TrimEnd()
    }

    return ($parts -join "`r`n`r`n")
}

function Copy-LegacyItem {
    param(
        [string]$Name
    )

    $source = Join-Path $brandBibleRoot $Name
    $destination = Join-Path $legacyRoot $Name

    if (-not (Test-Path -LiteralPath $source)) {
        return
    }

    if (Test-Path -LiteralPath $destination) {
        return
    }

    Copy-Item -LiteralPath $source -Destination $destination -Recurse -Force
}

if (-not (Test-Path -LiteralPath $brandBibleRoot)) {
    throw "BrandBible não encontrado em $brandBibleRoot"
}

New-Item -ItemType Directory -Path $legacyRoot -Force | Out-Null

$legacyItems = @(
    'README.md',
    'core',
    'avatar',
    'communication',
    'marketing',
    'design',
    'library',
    'product',
    'sales',
    'operations',
    'cases',
    'resources'
)

foreach ($item in $legacyItems) {
    Copy-LegacyItem -Name $item
}

$bundleMap = [ordered]@{
    'Core.md' = @(
        'core/Filosofia.md',
        'core/Posicionamento.md',
        'core/Valores.md',
        'core/Promessa.md',
        'core/Missao.md',
        'core/Visao.md',
        'core/Crenca.md',
        'core/Regras Fundamentais.md'
    )
    'Avatar.md' = @(
        'avatar/Avatar Principal.md',
        'avatar/Dores.md',
        'avatar/Objecoes.md',
        'avatar/Sonhos.md',
        'avatar/Linguagem.md'
    )
    'Comunicacao.md' = @(
        'communication/Tom de Voz.md',
        'communication/Copywriting.md',
        'communication/Psicologia do Conteudo.md',
        'communication/Storytelling.md',
        'communication/CTA.md',
        'resources/Templates/Legendas.md'
    )
    'Marketing.md' = @(
        'marketing/Carrosseis.md',
        'marketing/Reels.md',
        'marketing/Stories.md',
        'marketing/Landing Pages.md',
        'marketing/Conteudo.md',
        'marketing/Calendario Editorial.md',
        'marketing/Frameworks.md',
        'resources/Referencias/Exemplos Rejeitados.md'
    )
    'Design.md' = @(
        'design/Direcao de Arte.md',
        'design/Hierarquia Visual.md',
        'design/Tipografia.md',
        'design/Espacamento.md',
        'design/Smartphone.md',
        'design/Fotografia.md',
        'design/Mockups.md',
        'design/Cores.md',
        'design/Componentes.md',
        'resources/Prompts/Prompts Visuais.md',
        'resources/Prompts/Avaliacao de Arte.md'
    )
    'Produto.md' = @(
        'product/Produto.md',
        'product/Funcionalidades.md',
        'product/Beneficios.md',
        'product/Roadmap.md',
        'product/Integracoes.md',
        'product/Limitacoes.md'
    )
    'Comercial.md' = @(
        'sales/Prospecção.md',
        'sales/Direct.md',
        'sales/Diagnóstico.md',
        'sales/Apresentação.md',
        'sales/Objeções.md',
        'sales/Follow-up.md',
        'sales/Encerramento.md',
        'sales/Conversas Reais.md'
    )
    'Sistema.md' = @(
        'operations/Sistema Operacional.md',
        'operations/Processo Criativo.md',
        'operations/Processo de Decisao.md',
        'operations/Checklist.md',
        'operations/Fluxos.md'
    )
    'Bibliotecas.md' = @(
        'library/Ganchos.md',
        'library/Dores.md',
        'library/Observacoes.md',
        'library/Analogias.md',
        'library/Crencas.md',
        'library/Perguntas.md',
        'library/Headlines.md',
        'library/CTA.md',
        'library/Objeções.md',
        'library/FAQ.md',
        'resources/Templates/Respostas Publicas.md'
    )
    'CasosReais.md' = @(
        'cases/Carrosseis/Aprovados.md',
        'cases/Stories/Aprovados.md',
        'cases/Reels/Aprovados.md',
        'resources/Referencias/Exemplos Aprovados.md'
    )
}

foreach ($bundleName in $bundleMap.Keys) {
    $targetPath = Join-Path $brandBibleRoot $bundleName
    $bundleContent = Read-Docs -RelativePaths $bundleMap[$bundleName]
    Write-Utf8File -Path $targetPath -Text $bundleContent
}

$readme = @(
    '# BrandBible',
    '',
    '## Visão geral da documentação',
    '',
    'Esta organização foi consolidada para uso como Knowledge em um GPT personalizado da OpenAI, priorizando poucos arquivos grandes, recuperação simples de contexto e navegação direta.',
    '',
    'A estrutura nova mantém uma camada principal com aproximadamente 10 arquivos amplos e preserva a documentação modular anterior dentro de `legacy/`.',
    '',
    '## Descrição de cada arquivo',
    '',
    '- [Core.md](./Core.md): filosofia, missão, visão, valores, posicionamento, promessa, crenças e regras fundamentais.',
    '- [Avatar.md](./Avatar.md): avatar, dores, desejos, objeções, linguagem e contexto do público.',
    '- [Comunicacao.md](./Comunicacao.md): tom de voz, copywriting, storytelling, CTA, escrita e psicologia da comunicação.',
    '- [Marketing.md](./Marketing.md): carrosséis, reels, stories, landing pages, calendário editorial, estratégias e frameworks.',
    '- [Design.md](./Design.md): direção de arte, identidade visual, tipografia, composição, mockups, smartphone, fotografia, cores e componentes.',
    '- [Produto.md](./Produto.md): produto, funcionalidades, benefícios, integrações, roadmap e limitações.',
    '- [Comercial.md](./Comercial.md): prospecção, direct, diagnóstico, apresentação, objeções, follow-up, encerramento e análise de conversas.',
    '- [Sistema.md](./Sistema.md): processo criativo, sistema operacional, checklists, fluxos, decisão e metodologia de trabalho.',
    '- [Bibliotecas.md](./Bibliotecas.md): headlines, hooks, analogias, CTA, perguntas, objeções, FAQ, repertório e bibliotecas de apoio.',
    '- [CasosReais.md](./CasosReais.md): casos aprovados preservados por títulos.',
    '',
    '## Quando utilizar cada um',
    '',
    '- Use [Core.md](./Core.md) quando o GPT precisar decidir com base em identidade, posicionamento, promessa ou princípios da marca.',
    '- Use [Avatar.md](./Avatar.md) quando o foco for público, dor, linguagem, objeção ou perfil.',
    '- Use [Comunicacao.md](./Comunicacao.md) quando a tarefa envolver escrita, tom, CTA, legenda ou estrutura de comunicação.',
    '- Use [Marketing.md](./Marketing.md) quando a tarefa envolver formatos, distribuição, funil, campanha ou frameworks editoriais.',
    '- Use [Design.md](./Design.md) quando a tarefa envolver visual, prompts de imagem, direção de arte ou critérios gráficos.',
    '- Use [Produto.md](./Produto.md) quando a tarefa depender de entendimento do produto, funcionalidades e limitações.',
    '- Use [Comercial.md](./Comercial.md) quando a tarefa envolver venda, prospecção, apresentação ou tratamento de objeções.',
    '- Use [Sistema.md](./Sistema.md) quando o GPT precisar seguir método, checklist, fluxo ou padrão operacional.',
    '- Use [Bibliotecas.md](./Bibliotecas.md) quando for necessário acessar repertório pronto, hooks, objeções, perguntas e FAQ.',
    '- Use [CasosReais.md](./CasosReais.md) quando for útil ancorar a saída em exemplos aprovados.',
    '',
    '## Mapa da documentação',
    '',
    '```text',
    'BrandBible/',
    '├── README.md',
    '├── Core.md',
    '├── Avatar.md',
    '├── Comunicacao.md',
    '├── Marketing.md',
    '├── Design.md',
    '├── Produto.md',
    '├── Comercial.md',
    '├── Sistema.md',
    '├── Bibliotecas.md',
    '├── CasosReais.md',
    '└── legacy/',
    '```',
    '',
    '## Índice',
    '',
    '1. [Core.md](./Core.md)',
    '2. [Avatar.md](./Avatar.md)',
    '3. [Comunicacao.md](./Comunicacao.md)',
    '4. [Marketing.md](./Marketing.md)',
    '5. [Design.md](./Design.md)',
    '6. [Produto.md](./Produto.md)',
    '7. [Comercial.md](./Comercial.md)',
    '8. [Sistema.md](./Sistema.md)',
    '9. [Bibliotecas.md](./Bibliotecas.md)',
    '10. [CasosReais.md](./CasosReais.md)',
    '',
    '## Legacy',
    '',
    '- [legacy/README.md](./legacy/README.md)',
    '- [legacy/core/INDEX.md](./legacy/core/INDEX.md)',
    '- [legacy/avatar/INDEX.md](./legacy/avatar/INDEX.md)',
    '- [legacy/communication/INDEX.md](./legacy/communication/INDEX.md)',
    '- [legacy/marketing/INDEX.md](./legacy/marketing/INDEX.md)',
    '- [legacy/design/INDEX.md](./legacy/design/INDEX.md)',
    '- [legacy/library/INDEX.md](./legacy/library/INDEX.md)',
    '- [legacy/product/INDEX.md](./legacy/product/INDEX.md)',
    '- [legacy/sales/INDEX.md](./legacy/sales/INDEX.md)',
    '- [legacy/operations/INDEX.md](./legacy/operations/INDEX.md)',
    '- [legacy/cases/INDEX.md](./legacy/cases/INDEX.md)',
    '- [legacy/resources/INDEX.md](./legacy/resources/INDEX.md)'
)

Write-Utf8File -Path (Join-Path $brandBibleRoot 'README.md') -Text (($readme -join "`r`n").TrimEnd())
