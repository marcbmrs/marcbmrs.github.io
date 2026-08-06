$ErrorActionPreference = 'Stop'

$root = 'C:\marcbmrs.github.io'
$sourcePath = Join-Path $root 'MARCAR_HORA_BRAND_BIBLE.md'
$outputRoot = Join-Path $root 'BrandBible'

$content = Get-Content -LiteralPath $sourcePath -Encoding UTF8 -Raw
$lines = $content -split "`r?`n"

$chapterStarts = @()
for ($i = 0; $i -lt $lines.Count; $i++) {
    if ($lines[$i] -match '^# CAPÍTULO \d+$') {
        $chapterStarts += $i
    }
}

$chapters = @()
for ($i = 0; $i -lt $chapterStarts.Count; $i++) {
    $start = $chapterStarts[$i]
    $end = if ($i -lt $chapterStarts.Count - 1) { $chapterStarts[$i + 1] - 1 } else { $lines.Count - 1 }
    $chapterNumber = [int]($lines[$start] -replace '^# CAPÍTULO ', '')
    $chapterTitle = $lines[$start + 1] -replace '^# ', ''
    $chapterLines = $lines[$start..$end]

    $sections = @()
    $sectionStarts = @()
    for ($j = $start + 2; $j -le $end; $j++) {
        if ($lines[$j] -match '^# ' -and
            $lines[$j] -notmatch '^# CAPÍTULO ' -and
            $lines[$j] -ne "# $chapterTitle") {
            $sectionStarts += $j
        }
    }

    for ($j = 0; $j -lt $sectionStarts.Count; $j++) {
        $sectionStart = $sectionStarts[$j]
        $sectionEnd = if ($j -lt $sectionStarts.Count - 1) { $sectionStarts[$j + 1] - 1 } else { $end }
        $sectionTitle = $lines[$sectionStart] -replace '^# ', ''
        $sectionText = ($lines[$sectionStart..$sectionEnd] -join "`r`n").TrimEnd()
        $sections += [pscustomobject]@{
            Title = $sectionTitle
            Text  = $sectionText
        }
    }

    $chapters += [pscustomobject]@{
        Number   = $chapterNumber
        Title    = $chapterTitle
        Text     = ($chapterLines -join "`r`n").TrimEnd()
        Sections = $sections
    }
}

function New-Slug {
    param([string]$Text)

    $value = $Text.ToLowerInvariant()
    $map = @{
        'á' = 'a'; 'à' = 'a'; 'â' = 'a'; 'ã' = 'a'
        'é' = 'e'; 'ê' = 'e'
        'í' = 'i'
        'ó' = 'o'; 'ô' = 'o'; 'õ' = 'o'
        'ú' = 'u'
        'ç' = 'c'
    }

    foreach ($key in $map.Keys) {
        $value = $value.Replace($key, $map[$key])
    }

    $value = $value -replace '[^a-z0-9]+', '-'
    $value = $value.Trim('-')
    return $value
}

function Write-Utf8File {
    param(
        [string]$Path,
        [string]$Text
    )

    $dir = Split-Path -Parent $Path
    if ($dir -and -not (Test-Path -LiteralPath $dir)) {
        New-Item -ItemType Directory -Path $dir | Out-Null
    }
    [System.IO.File]::WriteAllText($Path, $Text + "`r`n", [System.Text.UTF8Encoding]::new($false))
}

if (-not (Test-Path -LiteralPath $outputRoot)) {
    New-Item -ItemType Directory -Path $outputRoot | Out-Null
}

$directories = @(
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
    'cases\Carrosseis',
    'cases\Stories',
    'cases\Reels',
    'cases\Landing Pages',
    'cases\Campanhas',
    'cases\Conversas',
    'resources',
    'resources\Prompts',
    'resources\Templates',
    'resources\Referencias',
    'resources\Assets'
)

foreach ($dir in $directories) {
    New-Item -ItemType Directory -Path (Join-Path $outputRoot $dir) -Force | Out-Null
}

$docMap = [ordered]@{
    'core/Filosofia.md' = @{
        Title = 'Filosofia'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'O que é o Marcar Hora?' },
            @{ Chapter = 1; Title = 'A verdadeira transformação' },
            @{ Chapter = 1; Title = 'O inimigo da marca' },
            @{ Chapter = 1; Title = 'O propósito' },
            @{ Chapter = 1; Title = 'O que o Marcar Hora NÃO é' },
            @{ Chapter = 1; Title = 'Como queremos que a marca seja percebida' },
            @{ Chapter = 1; Title = 'Como NÃO queremos ser percebidos' },
            @{ Chapter = 1; Title = 'O sentimento que toda publicação deve gerar' },
            @{ Chapter = 14; Title = 'FILOSOFIA' }
        )
        Description = 'Fundamentos filosóficos da marca e princípios-base de percepção.'
        Related = @('../core/Posicionamento.md', '../core/Promessa.md', '../core/Regras%20Fundamentais.md')
    }
    'core/Posicionamento.md' = @{
        Title = 'Posicionamento'
        Mode = 'chapter'
        Chapter = 2
        Description = 'Posicionamento central, território da marca e critérios de aprovação editorial.'
        Related = @('../core/Filosofia.md', '../core/Promessa.md', '../communication/Tom%20de%20Voz.md')
    }
    'core/Valores.md' = @{
        Title = 'Valores'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'Valores' }
        )
        Description = 'Valores explícitos que orientam comunicação, produto e comportamento.'
        Related = @('../core/Filosofia.md', '../core/Regras%20Fundamentais.md')
    }
    'core/Promessa.md' = @{
        Title = 'Promessa'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 2; Title = 'A promessa da marca' },
            @{ Chapter = 2; Title = 'A frase que resume todo o posicionamento' }
        )
        Description = 'Formulação oficial da promessa da marca e sua frase-guia.'
        Related = @('../core/Posicionamento.md', '../core/Missao.md', '../core/Visao.md')
    }
    'core/Missao.md' = @{
        Title = 'Missão'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'A missão' }
        )
        Description = 'Missão institucional da marca.'
        Related = @('../core/Visao.md', '../core/Promessa.md')
    }
    'core/Visao.md' = @{
        Title = 'Visao'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'A visão' }
        )
        Description = 'Visão de longo prazo da marca.'
        Related = @('../core/Missao.md', '../core/Posicionamento.md')
    }
    'core/Crenca.md' = @{
        Title = 'Crença'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'A crença central' }
        )
        Description = 'Crença central que sustenta todo o sistema de comunicação.'
        Related = @('../library/Crencas.md', '../core/Filosofia.md')
    }
    'core/Regras Fundamentais.md' = @{
        Title = 'Regras Fundamentais'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 1; Title = 'Regra de ouro' },
            @{ Chapter = 14; Title = 'REGRA MÁXIMA' },
            @{ Chapter = 14; Title = 'DECISÃO OBRIGATÓRIA' }
        )
        Description = 'Regras-mãe que não podem ser quebradas no sistema da marca.'
        Related = @('../operations/Processo%20de%20Decisao.md', '../operations/Checklist.md')
    }
    'avatar/Avatar Principal.md' = @{
        Title = 'Avatar Principal'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 3; Title = 'Quem é a cliente do Marcar Hora?' },
            @{ Chapter = 3; Title = 'Avatar Principal' },
            @{ Chapter = 3; Title = 'Características' },
            @{ Chapter = 3; Title = 'O que ela faz durante um atendimento?' },
            @{ Chapter = 3; Title = 'O maior problema' },
            @{ Chapter = 3; Title = 'O que ela pensa' },
            @{ Chapter = 3; Title = 'O que ela sente' },
            @{ Chapter = 3; Title = 'O erro que ela acredita cometer' },
            @{ Chapter = 3; Title = 'Medos' },
            @{ Chapter = 3; Title = 'O que ela valoriza' },
            @{ Chapter = 3; Title = 'O que ela odeia' },
            @{ Chapter = 3; Title = 'O que ela realmente compra' },
            @{ Chapter = 3; Title = 'Como queremos que ela se sinta' }
        )
        Description = 'Definição do avatar principal, rotina, emoções e contexto de compra.'
        Related = @('../avatar/Dores.md', '../avatar/Objecoes.md', '../avatar/Sonhos.md')
    }
    'avatar/Dores.md' = @{
        Title = 'Dores'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 3; Title = 'O que ela fala' },
            @{ Chapter = 3; Title = 'Objetivo emocional' },
            @{ Chapter = 10; Title = 'FILOSOFIA' },
            @{ Chapter = 10; Title = 'DOR 001' },
            @{ Chapter = 10; Title = 'DOR 002' },
            @{ Chapter = 10; Title = 'DOR 003' },
            @{ Chapter = 10; Title = 'DOR 004' },
            @{ Chapter = 10; Title = 'DOR 005' },
            @{ Chapter = 10; Title = 'DOR 006' },
            @{ Chapter = 10; Title = 'DOR 007' },
            @{ Chapter = 10; Title = 'DOR 008' },
            @{ Chapter = 10; Title = 'DOR 009' },
            @{ Chapter = 10; Title = 'DOR 010' },
            @{ Chapter = 10; Title = 'REGRA' }
        )
        Description = 'Dores declaradas e biblioteca completa de dores reutilizáveis.'
        Related = @('../library/Dores.md', '../avatar/Avatar%20Principal.md')
    }
    'avatar/Objecoes.md' = @{
        Title = 'Objeções'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 3; Title = 'Objeções para usar um sistema' }
        )
        Description = 'Objeções-base do avatar antes da etapa comercial.'
        Related = @('../sales/Objeções.md', '../library/Objeções.md')
    }
    'avatar/Sonhos.md' = @{
        Title = 'Sonhos'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 3; Title = 'Sonhos' }
        )
        Description = 'Aspirações e estados desejados pelo avatar principal.'
        Related = @('../avatar/Avatar%20Principal.md')
    }
    'avatar/Linguagem.md' = @{
        Title = 'Linguagem'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 3; Title = 'Como escrever para ela' },
            @{ Chapter = 3; Title = 'Linguagem' },
            @{ Chapter = 3; Title = 'Nunca presumir' }
        )
        Description = 'Forma correta de escrever para o avatar sem perder naturalidade.'
        Related = @('../communication/Tom%20de%20Voz.md', '../communication/Copywriting.md')
    }
    'communication/Tom de Voz.md' = @{
        Title = 'Tom de Voz'
        Mode = 'chapter'
        Chapter = 4
        Description = 'Manual completo de tom de voz, ritmo, vocabulário e regras de clareza.'
        Related = @('../avatar/Linguagem.md', '../communication/Copywriting.md')
    }
    'communication/Copywriting.md' = @{
        Title = 'Copywriting'
        Mode = 'chapter'
        Chapter = 5
        Description = 'Princípios de copy para conteúdos, legendas, stories e reels.'
        Related = @('../communication/Tom%20de%20Voz.md', '../communication/CTA.md')
    }
    'communication/Psicologia do Conteudo.md' = @{
        Title = 'Psicologia do Conteúdo'
        Mode = 'chapter'
        Chapter = 6
        Description = 'Princípios psicológicos que fazem um conteúdo parar, salvar, compartilhar e vender.'
        Related = @('../marketing/Frameworks.md', '../communication/Storytelling.md')
    }
    'communication/Storytelling.md' = @{
        Title = 'Storytelling'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 6; Title = 'O cérebro gosta de terminar histórias' },
            @{ Chapter = 7; Title = 'Framework Oficial 05' },
            @{ Chapter = 7; Title = 'A História' }
        )
        Description = 'Trechos sobre construção narrativa e uso de história como estrutura.'
        Related = @('../marketing/Carrosseis.md', '../marketing/Frameworks.md')
    }
    'communication/CTA.md' = @{
        Title = 'CTA'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 5; Title = 'O CTA' },
            @{ Chapter = 25; Title = 'CTAs OFICIAIS' },
            @{ Chapter = 25; Title = 'CTA PARA COMENTÁRIOS' },
            @{ Chapter = 25; Title = 'CTA PARA SALVAMENTO' },
            @{ Chapter = 25; Title = 'CTA PARA COMPARTILHAMENTO' },
            @{ Chapter = 25; Title = 'CTA PARA STORIES' },
            @{ Chapter = 25; Title = 'CTA PARA DIRECT' },
            @{ Chapter = 25; Title = 'CTA PARA LINK DA BIO' },
            @{ Chapter = 25; Title = 'CTA PARA TESTE' },
            @{ Chapter = 25; Title = 'CTA PARA CONTEÚDO SEM PRODUTO' },
            @{ Chapter = 25; Title = 'QUANTIDADE DE CTAs' }
        )
        Description = 'Regras e biblioteca oficial de CTAs por contexto.'
        Related = @('../library/CTA.md', '../communication/Copywriting.md')
    }
    'marketing/Carrosseis.md' = @{
        Title = 'Carrosséis'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 5; Title = 'A estrutura oficial dos carrosséis' },
            @{ Chapter = 7; Title = 'Objetivo' },
            @{ Chapter = 7; Title = 'Framework Oficial 01' },
            @{ Chapter = 7; Title = 'A Descoberta' },
            @{ Chapter = 7; Title = 'Framework Oficial 02' },
            @{ Chapter = 7; Title = 'O Espelho' },
            @{ Chapter = 7; Title = 'Framework Oficial 03' },
            @{ Chapter = 7; Title = 'Antes e Depois' },
            @{ Chapter = 7; Title = 'Framework Oficial 04' },
            @{ Chapter = 7; Title = 'A Pergunta' },
            @{ Chapter = 7; Title = 'Framework Oficial 05' },
            @{ Chapter = 7; Title = 'A História' },
            @{ Chapter = 7; Title = 'Framework Oficial 06' },
            @{ Chapter = 7; Title = 'O Mito' },
            @{ Chapter = 7; Title = 'Framework Oficial 07' },
            @{ Chapter = 7; Title = 'O Erro Invisível' },
            @{ Chapter = 7; Title = 'Framework Oficial 08' },
            @{ Chapter = 7; Title = 'O Cálculo' },
            @{ Chapter = 7; Title = 'Framework Oficial 09' },
            @{ Chapter = 7; Title = 'O Contraste' },
            @{ Chapter = 7; Title = 'Framework Oficial 10' },
            @{ Chapter = 7; Title = 'O Futuro' },
            @{ Chapter = 7; Title = 'Ritmo' },
            @{ Chapter = 7; Title = 'Quantidade' },
            @{ Chapter = 7; Title = 'Texto' },
            @{ Chapter = 7; Title = 'Hierarquia' },
            @{ Chapter = 7; Title = 'O primeiro slide' },
            @{ Chapter = 7; Title = 'O último slide' },
            @{ Chapter = 7; Title = 'Checklist' }
        )
        Description = 'Sistema completo de criação e avaliação de carrosséis.'
        Related = @('../marketing/Frameworks.md', '../cases/Carrosseis/INDEX.md')
    }
    'marketing/Reels.md' = @{
        Title = 'Reels'
        Mode = 'chapter'
        Chapter = 8
        Description = 'Frameworks, ritmo e critérios de produção de reels.'
        Related = @('../marketing/Frameworks.md', '../cases/Reels/INDEX.md')
    }
    'marketing/Stories.md' = @{
        Title = 'Stories'
        Mode = 'chapter'
        Chapter = 9
        Description = 'Sistema operacional de stories com estrutura diária, formatos e checklist.'
        Related = @('../marketing/Calendario%20Editorial.md', '../cases/Stories/INDEX.md')
    }
    'marketing/Landing Pages.md' = @{
        Title = 'Landing Pages'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 27; Title = 'QUANDO O USUÁRIO PEDIR UMA LANDING PAGE' }
        )
        Description = 'Diretriz operacional para pedidos de landing page.'
        Related = @('../product/Beneficios.md', '../communication/Copywriting.md')
    }
    'marketing/Conteudo.md' = @{
        Title = 'Conteúdo'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 19; Title = 'PLAYBOOK OFICIAL DE CRIAÇÃO DE CONTEÚDO' }
        )
        Description = 'Playbook de criação de conteúdo do início ao fim.'
        Related = @('../marketing/Calendario%20Editorial.md', '../operations/Processo%20Criativo.md')
    }
    'marketing/Calendario Editorial.md' = @{
        Title = 'Calendário Editorial'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 16; Title = 'SISTEMA DE DECISÃO DE CONTEÚDO' }
        )
        Description = 'Sistema de decisão editorial, equilíbrio de objetivos e análise de histórico.'
        Related = @('../marketing/Conteudo.md', '../operations/Processo%20de%20Decisao.md')
    }
    'marketing/Frameworks.md' = @{
        Title = 'Frameworks'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 18; Title = 'ÁRVORE DE DECISÃO DO GPT' },
            @{ Chapter = 18; Title = 'ÁRVORE PRINCIPAL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA CARROSSEL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA REEL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA STORIES' },
            @{ Chapter = 18; Title = 'FLUXO DE REVISÃO' },
            @{ Chapter = 18; Title = 'FORMATO INTERNO DE DIAGNÓSTICO' },
            @{ Chapter = 18; Title = 'REGRA DE DISCORDÂNCIA' },
            @{ Chapter = 18; Title = 'REGRA FINAL' }
        )
        Description = 'Árvore de decisão e fluxos oficiais por formato de conteúdo.'
        Related = @('../marketing/Carrosseis.md', '../marketing/Reels.md', '../marketing/Stories.md')
    }
    'design/Direcao de Arte.md' = @{
        Title = 'Direção de Arte'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'OBJETIVO' },
            @{ Chapter = 26; Title = 'PRINCÍPIO CENTRAL' },
            @{ Chapter = 26; Title = 'IDENTIDADE VISUAL PRINCIPAL' },
            @{ Chapter = 26; Title = 'REFERÊNCIAS DE ESTILO' },
            @{ Chapter = 26; Title = 'SISTEMA DE VARIAÇÃO VISUAL' },
            @{ Chapter = 26; Title = 'DISTRIBUIÇÃO EDITORIAL RECOMENDADA' },
            @{ Chapter = 26; Title = 'COMO ESCOLHER ENTRE TEXTO E IMAGEM' },
            @{ Chapter = 26; Title = 'TESTE DE NECESSIDADE DO ELEMENTO' },
            @{ Chapter = 26; Title = 'TESTE DE REMOÇÃO' },
            @{ Chapter = 26; Title = 'TESTE DE MINIATURA' },
            @{ Chapter = 26; Title = 'TESTE DE TRÊS SEGUNDOS' },
            @{ Chapter = 26; Title = 'CRÍTICA VISUAL' },
            @{ Chapter = 26; Title = 'VARIAÇÃO SEM PERDA DE IDENTIDADE' },
            @{ Chapter = 26; Title = 'REGRA DE CONSISTÊNCIA' },
            @{ Chapter = 26; Title = 'REGRA FINAL' }
        )
        Description = 'Direção de arte, consistência visual e critérios de avaliação.'
        Related = @('../design/Cores.md', '../design/Tipografia.md', '../resources/Prompts/INDEX.md')
    }
    'design/Hierarquia Visual.md' = @{
        Title = 'Hierarquia Visual'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'HIERARQUIA VISUAL' }
        )
        Description = 'Princípios de hierarquia visual.'
        Related = @('../design/Tipografia.md', '../design/Componentes.md')
    }
    'design/Tipografia.md' = @{
        Title = 'Tipografia'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'TIPOGRAFIA' },
            @{ Chapter = 26; Title = 'TIPO VISUAL 01' },
            @{ Chapter = 26; Title = 'TIPOGRAFIA PREDOMINANTE' }
        )
        Description = 'Sistema tipográfico e uso de capas tipográficas.'
        Related = @('../design/Hierarquia%20Visual.md', '../resources/Prompts/INDEX.md')
    }
    'design/Espacamento.md' = @{
        Title = 'Espaçamento'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'ESPAÇO EM BRANCO' }
        )
        Description = 'Uso de respiro e espaço em branco.'
        Related = @('../design/Hierarquia%20Visual.md')
    }
    'design/Smartphone.md' = @{
        Title = 'Smartphone'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'USO DE SMARTPHONE' }
        )
        Description = 'Regras para representação e uso visual de smartphone.'
        Related = @('../design/Mockups.md', '../design/Componentes.md')
    }
    'design/Fotografia.md' = @{
        Title = 'Fotografia'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'TIPO VISUAL 02' },
            @{ Chapter = 26; Title = 'FOTOGRAFIA REALISTA' },
            @{ Chapter = 26; Title = 'FOTOGRAFIA E REPRESENTAÇÃO DO NICHO' },
            @{ Chapter = 26; Title = 'HUMANIDADE VISUAL' }
        )
        Description = 'Fotografia realista, representação do nicho e humanidade visual.'
        Related = @('../resources/Prompts/INDEX.md', '../design/Direcao%20de%20Arte.md')
    }
    'design/Mockups.md' = @{
        Title = 'Mockups'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'TIPO VISUAL 04' },
            @{ Chapter = 26; Title = 'INTERFACE REAL DO PRODUTO' }
        )
        Description = 'Uso de mockups e interface real do produto.'
        Related = @('../design/Smartphone.md', '../product/Funcionalidades.md')
    }
    'design/Cores.md' = @{
        Title = 'Cores'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'PALETA PRINCIPAL' },
            @{ Chapter = 26; Title = 'FUNDO' }
        )
        Description = 'Paleta, fundo e diretrizes de cor.'
        Related = @('../design/Direcao%20de%20Arte.md')
    }
    'design/Componentes.md' = @{
        Title = 'Componentes'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'TIPO VISUAL 03' },
            @{ Chapter = 26; Title = 'ILUSTRAÇÃO MINIMALISTA' },
            @{ Chapter = 26; Title = 'TIPO VISUAL 05' },
            @{ Chapter = 26; Title = 'ELEMENTOS CONTEXTUAIS' },
            @{ Chapter = 26; Title = 'TIPO VISUAL 06' },
            @{ Chapter = 26; Title = 'COMPARAÇÃO VISUAL' },
            @{ Chapter = 26; Title = 'TIPO VISUAL 07' },
            @{ Chapter = 26; Title = 'CÁLCULO E DADOS' },
            @{ Chapter = 26; Title = 'ELEMENTOS QUE DEVEM SER EVITADOS' },
            @{ Chapter = 26; Title = 'USO DA LOGO' },
            @{ Chapter = 26; Title = 'ACESSIBILIDADE' },
            @{ Chapter = 26; Title = 'FORMATOS' },
            @{ Chapter = 26; Title = 'SISTEMA DE CAPAS' }
        )
        Description = 'Componentes visuais, ilustrações, comparações, capas e regras de uso.'
        Related = @('../design/Hierarquia%20Visual.md', '../design/Mockups.md')
    }
    'library/Ganchos.md' = @{
        Title = 'Ganchos'
        Mode = 'chapter'
        Chapter = 11
        Description = 'Biblioteca de ganchos por categoria e checklist de uso.'
        Related = @('../library/Observacoes.md', '../library/Perguntas.md')
    }
    'library/Dores.md' = @{
        Title = 'Dores'
        Mode = 'chapter'
        Chapter = 10
        Description = 'Biblioteca oficial de dores da marca.'
        Related = @('../avatar/Dores.md', '../library/Objeções.md')
    }
    'library/Observacoes.md' = @{
        Title = 'Observações'
        Mode = 'chapter'
        Chapter = 12
        Description = 'Banco de observações prontas para conteúdos e abordagens.'
        Related = @('../library/Ganchos.md', '../library/Analogias.md')
    }
    'library/Analogias.md' = @{
        Title = 'Analogias'
        Mode = 'chapter'
        Chapter = 20
        Description = 'Trechos e estrutura de uso de analogias no conteúdo.'
        Related = @('../communication/Copywriting.md', '../library/Observacoes.md')
    }
    'library/Crencas.md' = @{
        Title = 'Crenças'
        Mode = 'chapter'
        Chapter = 13
        Description = 'Banco de crenças com crença atual, nova crença e conteúdos possíveis.'
        Related = @('../core/Crenca.md', '../library/Objeções.md')
    }
    'library/Perguntas.md' = @{
        Title = 'Perguntas'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 11; Title = 'CATEGORIA 5' }
        )
        Description = 'Biblioteca de perguntas dentro do sistema de ganchos.'
        Related = @('../library/Ganchos.md', '../communication/Tom%20de%20Voz.md')
    }
    'library/Headlines.md' = @{
        Title = 'Headlines'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 25; Title = 'REGRA DE ABERTURA' },
            @{ Chapter = 25; Title = 'ABERTURAS REJEITADAS' }
        )
        Description = 'Regras de abertura e padrões rejeitados de headlines.'
        Related = @('../library/Ganchos.md', '../communication/Copywriting.md')
    }
    'library/CTA.md' = @{
        Title = 'CTA'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 25; Title = 'CTAs OFICIAIS' },
            @{ Chapter = 25; Title = 'CTA PARA COMENTÁRIOS' },
            @{ Chapter = 25; Title = 'CTA PARA SALVAMENTO' },
            @{ Chapter = 25; Title = 'CTA PARA COMPARTILHAMENTO' },
            @{ Chapter = 25; Title = 'CTA PARA STORIES' },
            @{ Chapter = 25; Title = 'CTA PARA DIRECT' },
            @{ Chapter = 25; Title = 'CTA PARA LINK DA BIO' },
            @{ Chapter = 25; Title = 'CTA PARA TESTE' },
            @{ Chapter = 25; Title = 'CTA PARA CONTEÚDO SEM PRODUTO' }
        )
        Description = 'Biblioteca reaproveitável de CTAs oficiais.'
        Related = @('../communication/CTA.md')
    }
    'library/Objeções.md' = @{
        Title = 'Objeções'
        Mode = 'chapter'
        Chapter = 21
        Description = 'Biblioteca operacional de objeções e respostas em conteúdo.'
        Related = @('../sales/Objeções.md', '../avatar/Objecoes.md')
    }
    'library/FAQ.md' = @{
        Title = 'FAQ'
        Mode = 'chapter'
        Chapter = 22
        Description = 'Perguntas frequentes e respostas oficiais.'
        Related = @('../product/Funcionalidades.md', '../sales/Apresentação.md')
    }
    'product/Produto.md' = @{
        Title = 'Produto'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 2; Title = 'O papel do aplicativo' },
            @{ Chapter = 28; Title = 'COMO APRESENTAR O MARCAR HORA' }
        )
        Description = 'Papel do produto e manual de apresentação em contexto comercial.'
        Related = @('../product/Funcionalidades.md', '../sales/Apresentação.md')
    }
    'product/Funcionalidades.md' = @{
        Title = 'Funcionalidades'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 28; Title = 'O QUE MOSTRAR PRIMEIRO' },
            @{ Chapter = 28; Title = 'O QUE NÃO MOSTRAR PRIMEIRO' },
            @{ Chapter = 28; Title = 'QUANTAS FUNCIONALIDADES MOSTRAR' },
            @{ Chapter = 28; Title = 'AS TRÊS MELHORES' }
        )
        Description = 'Quais funcionalidades mostrar, quando e em que ordem.'
        Related = @('../product/Produto.md', '../product/Beneficios.md')
    }
    'product/Beneficios.md' = @{
        Title = 'Benefícios'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 2; Title = 'O trabalho que o cliente realmente compra' },
            @{ Chapter = 28; Title = 'APRESENTANDO O PRODUTO' },
            @{ Chapter = 28; Title = 'A REGRA DO "E DAÍ?"' }
        )
        Description = 'Benefícios reais e tradução de funcionalidades em valor percebido.'
        Related = @('../product/Funcionalidades.md', '../communication/Copywriting.md')
    }
    'product/Roadmap.md' = @{
        Title = 'Roadmap'
        Mode = 'empty'
        Description = 'Diretório reservado para evolução futura do produto; o material atual não possui capítulo dedicado.'
        Related = @('../product/Produto.md')
    }
    'product/Integracoes.md' = @{
        Title = 'Integrações'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 22; Title = 'FAQ 29' }
        )
        Description = 'Trecho atual relacionado a integrações.'
        Related = @('../library/FAQ.md')
    }
    'product/Limitacoes.md' = @{
        Title = 'Limitações'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 27; Title = 'LIMITES' }
        )
        Description = 'Limites operacionais explicitados no sistema do GPT.'
        Related = @('../operations/Sistema%20Operacional.md')
    }
    'sales/Prospecção.md' = @{
        Title = 'Prospecção'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'OBJETIVO'
        EndTitle = 'A PRIMEIRA MENSAGEM'
        Description = 'Critérios de prospecção, escolha de perfil e diagnóstico inicial.'
        Related = @('../sales/Direct.md', '../sales/Diagnóstico.md')
    }
    'sales/Direct.md' = @{
        Title = 'Direct'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'A PRIMEIRA MENSAGEM'
        EndTitle = 'O DIAGNÓSTICO'
        Description = 'Abordagem em direct, estrutura da primeira mensagem e abertura da conversa.'
        Related = @('../sales/Prospecção.md', '../sales/Diagnóstico.md')
    }
    'sales/Diagnóstico.md' = @{
        Title = 'Diagnóstico'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'O DIAGNÓSTICO'
        EndTitle = 'COMO APRESENTAR O MARCAR HORA'
        Description = 'Diagnóstico comercial, descoberta de dor e transição para apresentação.'
        Related = @('../sales/Apresentação.md', '../sales/Objeções.md')
    }
    'sales/Apresentação.md' = @{
        Title = 'Apresentação'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'COMO APRESENTAR O MARCAR HORA'
        EndTitle = 'COMO RESPONDER OBJEÇÕES NO DIRECT'
        Description = 'Apresentação do produto em contexto comercial.'
        Related = @('../product/Produto.md', '../sales/Diagnóstico.md')
    }
    'sales/Objeções.md' = @{
        Title = 'Objeções'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'COMO RESPONDER OBJEÇÕES NO DIRECT'
        EndTitle = 'FOLLOW-UP'
        Description = 'Tratamento de objeções em direct.'
        Related = @('../library/Objeções.md', '../avatar/Objecoes.md')
    }
    'sales/Follow-up.md' = @{
        Title = 'Follow-up'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'FOLLOW-UP'
        EndTitle = 'ENCERRAMENTO'
        Description = 'Critérios e modelos de follow-up respeitoso.'
        Related = @('../sales/Encerramento.md')
    }
    'sales/Encerramento.md' = @{
        Title = 'Encerramento'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'ENCERRAMENTO'
        EndTitle = 'CONVERSA 014'
        Description = 'Encerramento de conversa preservando reputação e porta aberta.'
        Related = @('../sales/Follow-up.md')
    }
    'sales/Conversas Reais.md' = @{
        Title = 'Conversas Reais'
        Mode = 'span'
        Chapter = 28
        StartTitle = 'CONVERSA 014'
        EndTitle = ''
        Description = 'Análise de conversas reais, aprendizados e evolução comercial.'
        Related = @('../cases/Conversas/INDEX.md')
    }
    'operations/Sistema Operacional.md' = @{
        Title = 'Sistema Operacional'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 27; Title = 'SISTEMA OPERACIONAL DO GPT' },
            @{ Chapter = 27; Title = 'IDENTIDADE' },
            @{ Chapter = 27; Title = 'MISSÃO' },
            @{ Chapter = 27; Title = 'COMO RESPONDER' },
            @{ Chapter = 27; Title = 'TOM DE VOZ' },
            @{ Chapter = 27; Title = 'NÍVEL DE EXIGÊNCIA' },
            @{ Chapter = 27; Title = 'MEMÓRIA DA CONVERSA' },
            @{ Chapter = 27; Title = 'LIMITES' },
            @{ Chapter = 27; Title = 'PRIORIDADES' },
            @{ Chapter = 27; Title = 'O QUE O GPT DEVE PROTEGER' },
            @{ Chapter = 27; Title = 'O QUE O GPT DEVE EVITAR' },
            @{ Chapter = 27; Title = 'DEFINIÇÃO DE SUCESSO' },
            @{ Chapter = 27; Title = 'REGRA FINAL' }
        )
        Description = 'Sistema operacional geral do GPT para a marca.'
        Related = @('../operations/Processo%20Criativo.md', '../operations/Processo%20de%20Decisao.md')
    }
    'operations/Processo Criativo.md' = @{
        Title = 'Processo Criativo'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 15; Title = 'PROCESSO DE RACIOCÍNIO' },
            @{ Chapter = 19; Title = 'PLAYBOOK OFICIAL DE CRIAÇÃO DE CONTEÚDO' }
        )
        Description = 'Processo criativo e fluxo de trabalho para criação de peças.'
        Related = @('../operations/Sistema%20Operacional.md', '../marketing/Conteudo.md')
    }
    'operations/Processo de Decisao.md' = @{
        Title = 'Processo de Decisão'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 15; Title = 'FILOSOFIA' },
            @{ Chapter = 15; Title = 'REGRA 1' },
            @{ Chapter = 15; Title = 'QUANDO O GPT DEVE DISCORDAR' },
            @{ Chapter = 15; Title = 'COMO DISCORDAR' },
            @{ Chapter = 15; Title = 'O GPT DEVE SER CRÍTICO' },
            @{ Chapter = 15; Title = 'O GPT DEVE PROTEGER A MARCA' },
            @{ Chapter = 15; Title = 'O GPT DEVE ENSINAR' },
            @{ Chapter = 15; Title = 'O GPT DEVE EVITAR' },
            @{ Chapter = 15; Title = 'O GPT DEVE BUSCAR' },
            @{ Chapter = 15; Title = 'O GPT DEVE PENSAR COMO UM DIRETOR DE MARKETING' },
            @{ Chapter = 15; Title = 'REGRA FINAL' },
            @{ Chapter = 16; Title = 'FILOSOFIA' },
            @{ Chapter = 16; Title = 'REGRA MÁXIMA' },
            @{ Chapter = 16; Title = 'MATRIZ DE DECISÃO' },
            @{ Chapter = 16; Title = 'O GPT DEVE PRIORIZAR' },
            @{ Chapter = 16; Title = 'ANTES DE APROVAR' },
            @{ Chapter = 16; Title = 'REGRA FINAL' }
        )
        Description = 'Critérios de decisão, discordância estratégica e priorização.'
        Related = @('../operations/Checklist.md', '../core/Regras%20Fundamentais.md')
    }
    'operations/Checklist.md' = @{
        Title = 'Checklist'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 4; Title = 'Checklist obrigatório' },
            @{ Chapter = 7; Title = 'Checklist' },
            @{ Chapter = 8; Title = 'Checklist' },
            @{ Chapter = 9; Title = 'CHECKLIST' },
            @{ Chapter = 11; Title = 'CHECKLIST' },
            @{ Chapter = 13; Title = 'CHECKLIST' },
            @{ Chapter = 25; Title = 'CHECKLIST DA LEGENDA' },
            @{ Chapter = 28; Title = 'CHECKLIST' }
        )
        Description = 'Compilado dos checklists operacionais distribuídos no material.'
        Related = @('../operations/Processo%20de%20Decisao.md')
    }
    'operations/Fluxos.md' = @{
        Title = 'Fluxos'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 18; Title = 'ÁRVORE PRINCIPAL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA CARROSSEL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA REEL' },
            @{ Chapter = 18; Title = 'FLUXO ESPECÍFICO PARA STORIES' },
            @{ Chapter = 18; Title = 'FLUXO DE REVISÃO' },
            @{ Chapter = 28; Title = 'EXEMPLO DE FLUXO' }
        )
        Description = 'Fluxos operacionais e árvores de execução.'
        Related = @('../marketing/Frameworks.md', '../operations/Checklist.md')
    }
    'cases/Carrosseis/Aprovados.md' = @{
        Title = 'Aprovados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 01' },
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 02' },
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 03' },
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 04' }
        )
        Description = 'Exemplos aprovados de carrosséis.'
        Related = @('../../marketing/Carrosseis.md')
    }
    'cases/Carrosseis/Rejeitados.md' = @{
        Title = 'Rejeitados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 01' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 02' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 03' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 04' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 14' }
        )
        Description = 'Exemplos rejeitados de carrosséis.'
        Related = @('../../marketing/Carrosseis.md')
    }
    'cases/Stories/Aprovados.md' = @{
        Title = 'Aprovados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 06' },
            @{ Chapter = 23; Title = 'STORY COM CAIXA DE PERGUNTAS' }
        )
        Description = 'Exemplos aprovados de stories.'
        Related = @('../../marketing/Stories.md')
    }
    'cases/Stories/Rejeitados.md' = @{
        Title = 'Rejeitados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 05' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 06' }
        )
        Description = 'Exemplos rejeitados de stories.'
        Related = @('../../marketing/Stories.md')
    }
    'cases/Reels/Aprovados.md' = @{
        Title = 'Aprovados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 23; Title = 'EXEMPLO APROVADO 02' }
        )
        Description = 'Exemplos aprovados de reels.'
        Related = @('../../marketing/Reels.md')
    }
    'cases/Reels/Rejeitados.md' = @{
        Title = 'Rejeitados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 07' },
            @{ Chapter = 24; Title = 'EXEMPLO REJEITADO 08' }
        )
        Description = 'Exemplos rejeitados de reels.'
        Related = @('../../marketing/Reels.md')
    }
    'cases/Landing Pages/INDEX.md' = @{
        Title = 'Landing Pages'
        Mode = 'custom'
        Text = @"
# Landing Pages

- O material atual não possui casos reais de landing pages isolados em capítulo próprio.
- O contexto relacionado aparece em [Landing Pages](../../marketing/Landing%20Pages.md).
"@
        Description = 'Ponto de navegação para futuros casos de landing pages.'
        Related = @('../../marketing/Landing%20Pages.md')
    }
    'cases/Campanhas/INDEX.md' = @{
        Title = 'Campanhas'
        Mode = 'custom'
        Text = @"
# Campanhas

- O material atual não possui um capítulo exclusivo de campanhas reais separado como biblioteca.
- Os frameworks e decisões de campanha aparecem em [Calendário Editorial](../../marketing/Calendario%20Editorial.md) e [Conteúdo](../../marketing/Conteudo.md).
"@
        Description = 'Ponto de navegação para campanhas e material correlato.'
        Related = @('../../marketing/Calendario%20Editorial.md', '../../marketing/Conteudo.md')
    }
    'cases/Conversas/Conversa 014.md' = @{
        Title = 'Conversa 014'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 28; Title = 'CONVERSA 014' }
        )
        Description = 'Caso real individual preservado como documento isolado.'
        Related = @('../../sales/Conversas%20Reais.md')
    }
    'cases/Conversas/Analise de Conversas.md' = @{
        Title = 'Análise de Conversas'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 28; Title = 'ANÁLISE DE CONVERSAS REAIS' },
            @{ Chapter = 28; Title = 'RELATÓRIO FINAL' },
            @{ Chapter = 28; Title = 'NÃO REESCREVER TUDO' },
            @{ Chapter = 28; Title = 'BANCO DE APRENDIZADOS' },
            @{ Chapter = 28; Title = 'EVOLUÇÃO CONTÍNUA' },
            @{ Chapter = 28; Title = 'REGRA FINAL' }
        )
        Description = 'Modelo de análise contínua de conversas comerciais.'
        Related = @('../../sales/Conversas%20Reais.md')
    }
    'resources/Prompts/Prompts Visuais.md' = @{
        Title = 'Prompts Visuais'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'PROMPT BASE PARA CAPA TIPOGRÁFICA' },
            @{ Chapter = 26; Title = 'PROMPT BASE PARA FOTOGRAFIA REALISTA' },
            @{ Chapter = 26; Title = 'PROMPT BASE PARA ILUSTRAÇÃO MINIMALISTA' },
            @{ Chapter = 26; Title = 'PROMPT BASE PARA INTERFACE REAL' },
            @{ Chapter = 26; Title = 'PROMPT BASE PARA STORY' },
            @{ Chapter = 26; Title = 'PROMPT BASE PARA MODIFICAÇÃO DE ARTE' },
            @{ Chapter = 26; Title = 'COMO O GPT DEVE CRIAR PROMPTS' },
            @{ Chapter = 26; Title = 'QUANDO O GPT DEVE PEDIR REFERÊNCIAS' }
        )
        Description = 'Prompts-base e regras para criação de imagens.'
        Related = @('../../design/Direcao%20de%20Arte.md')
    }
    'resources/Prompts/Avaliacao de Arte.md' = @{
        Title = 'Avaliação de Arte'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 26; Title = 'COMO O GPT DEVE AVALIAR UMA ARTE ENVIADA' }
        )
        Description = 'Checklist estrutural para avaliar artes enviadas.'
        Related = @('../../design/Direcao%20de%20Arte.md')
    }
    'resources/Templates/Legendas.md' = @{
        Title = 'Legendas'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 25; Title = 'ESTRUTURA OFICIAL DE LEGENDA' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA CARROSSEL EDUCATIVO' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA CARROSSEL DE POSICIONAMENTO' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA REELS DE DEMONSTRAÇÃO' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA POST ÚNICO' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA CONTEÚDO COM CÁLCULO' },
            @{ Chapter = 25; Title = 'LEGENDAS PARA CONTEÚDO DE PRODUTO' },
            @{ Chapter = 25; Title = 'FORMATO DE ENTREGA DO GPT' }
        )
        Description = 'Templates de legenda e estrutura de entrega.'
        Related = @('../../communication/CTA.md', '../../communication/Copywriting.md')
    }
    'resources/Templates/Respostas Publicas.md' = @{
        Title = 'Respostas Públicas'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 22; Title = 'FORMATO RECOMENDADO PARA RESPOSTAS PÚBLICAS' }
        )
        Description = 'Template oficial para respostas públicas.'
        Related = @('../../library/FAQ.md')
    }
    'resources/Referencias/Exemplos Aprovados.md' = @{
        Title = 'Exemplos Aprovados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 23; Title = 'BIBLIOTECA DE EXEMPLOS APROVADOS' },
            @{ Chapter = 23; Title = 'OBJETIVO' },
            @{ Chapter = 23; Title = 'PRINCÍPIO CENTRAL' },
            @{ Chapter = 23; Title = 'PADRÕES PRESENTES NOS EXEMPLOS APROVADOS' },
            @{ Chapter = 23; Title = 'COMO O GPT DEVE USAR ESTES EXEMPLOS' },
            @{ Chapter = 23; Title = 'CRITÉRIO DE APROVAÇÃO DE NOVOS EXEMPLOS' },
            @{ Chapter = 23; Title = 'REGRA FINAL' }
        )
        Description = 'Referência mestra sobre o uso dos exemplos aprovados.'
        Related = @('../../cases/Carrosseis/Aprovados.md')
    }
    'resources/Referencias/Exemplos Rejeitados.md' = @{
        Title = 'Exemplos Rejeitados'
        Mode = 'sections'
        Sections = @(
            @{ Chapter = 24; Title = 'BIBLIOTECA DE EXEMPLOS REJEITADOS' },
            @{ Chapter = 24; Title = 'OBJETIVO' },
            @{ Chapter = 24; Title = 'FILOSOFIA' },
            @{ Chapter = 24; Title = 'PADRÕES QUE DEVEM SER EVITADOS' },
            @{ Chapter = 24; Title = 'TESTE DE IDENTIDADE' },
            @{ Chapter = 24; Title = 'REGRA FINAL' }
        )
        Description = 'Referência mestra dos padrões rejeitados.'
        Related = @('../../cases/Carrosseis/Rejeitados.md')
    }
    'resources/Assets/INDEX.md' = @{
        Title = 'Assets'
        Mode = 'custom'
        Text = @"
# Assets

- O material original em Markdown não embute uma biblioteca física de assets dentro deste escopo.
- As referências visuais e prompts relacionados estão em [Direção de Arte](../../design/Direcao%20de%20Arte.md) e [Prompts Visuais](../Prompts/Prompts%20Visuais.md).
"@
        Description = 'Ponto de navegação para assets e referências associadas.'
        Related = @('../../design/Direcao%20de%20Arte.md', '../Prompts/Prompts%20Visuais.md')
    }
}

function Get-Chapter {
    param([int]$Number)
    return $chapters | Where-Object { $_.Number -eq $Number }
}

function Get-SectionText {
    param(
        [int]$Chapter,
        [string]$Title
    )
    $chapterObj = Get-Chapter -Number $Chapter
    if (-not $chapterObj) {
        throw "Capítulo $Chapter não encontrado."
    }
    if ($chapterObj.Title -eq $Title) {
        return $chapterObj.Text
    }

    $matches = @($chapterObj.Sections | Where-Object { $_.Title -eq $Title })
    if ($matches.Count -eq 0) {
        throw "Seção '$Title' não encontrada no capítulo $Chapter."
    }
    return $matches[0].Text
}

function Get-SpanText {
    param(
        [int]$Chapter,
        [string]$StartTitle,
        [string]$EndTitle
    )

    $chapterObj = Get-Chapter -Number $Chapter
    if (-not $chapterObj) {
        throw "Capítulo $Chapter não encontrado."
    }

    $sections = @($chapterObj.Sections)
    $startIndex = -1
    for ($i = 0; $i -lt $sections.Count; $i++) {
        if ($sections[$i].Title -eq $StartTitle) {
            $startIndex = $i
            break
        }
    }
    if ($startIndex -lt 0) {
        throw "Início '$StartTitle' não encontrado no capítulo $Chapter."
    }

    $endIndex = $sections.Count
    if ($EndTitle) {
        for ($i = $startIndex + 1; $i -lt $sections.Count; $i++) {
            if ($sections[$i].Title -eq $EndTitle) {
                $endIndex = $i
                break
            }
        }
    }

    return (($sections[$startIndex..($endIndex - 1)] | ForEach-Object { $_.Text }) -join "`r`n`r`n").TrimEnd()
}

foreach ($relativePath in $docMap.Keys) {
    $config = $docMap[$relativePath]
    $targetPath = Join-Path $outputRoot $relativePath
    $body = ''

    switch ($config.Mode) {
        'chapter' {
            $body = (Get-Chapter -Number $config.Chapter).Text
        }
        'sections' {
            $parts = @()
            foreach ($section in $config.Sections) {
                $parts += Get-SectionText -Chapter $section.Chapter -Title $section.Title
            }
            $body = ($parts -join "`r`n`r`n")
        }
        'span' {
            $body = Get-SpanText -Chapter $config.Chapter -StartTitle $config.StartTitle -EndTitle $config.EndTitle
        }
        'custom' {
            $body = $config.Text.TrimEnd()
        }
        'empty' {
            $body = "# $($config.Title)`r`n`r`n- O material original não possui um capítulo exclusivo para este tema."
        }
        default {
            throw "Modo não suportado: $($config.Mode)"
        }
    }

    Write-Utf8File -Path $targetPath -Text $body
}

$folderDescriptions = [ordered]@{
    '.' = 'Visão geral, navegação principal e leitura recomendada.'
    'core' = 'Fundamentos estratégicos e identidade central da marca.'
    'avatar' = 'Perfil do público, dores, objeções, sonhos e linguagem.'
    'communication' = 'Regras de voz, copy, psicologia do conteúdo, storytelling e CTA.'
    'marketing' = 'Estruturas de conteúdo, formatos, frameworks e decisão editorial.'
    'design' = 'Direção visual, componentes, fotografia e critérios estéticos.'
    'library' = 'Bibliotecas reutilizáveis de dores, ganchos, crenças, perguntas e FAQ.'
    'product' = 'Material sobre produto, benefícios, funcionalidades e limites.'
    'sales' = 'Sistema de prospecção, direct, diagnóstico, objeções e follow-up.'
    'operations' = 'Processos operacionais, checklists e fluxos do GPT e da marca.'
    'cases' = 'Separação de exemplos práticos e casos organizados por formato.'
    'cases\Carrosseis' = 'Casos reais e exemplos ligados a carrosséis.'
    'cases\Stories' = 'Casos reais e exemplos ligados a stories.'
    'cases\Reels' = 'Casos reais e exemplos ligados a reels.'
    'cases\Landing Pages' = 'Espaço reservado para casos de landing pages.'
    'cases\Campanhas' = 'Espaço reservado para campanhas e materiais correlatos.'
    'cases\Conversas' = 'Análises e casos reais de conversas comerciais.'
    'resources' = 'Materiais de apoio, prompts, templates, referências e assets.'
    'resources\Prompts' = 'Prompts e critérios de criação visual.'
    'resources\Templates' = 'Estruturas prontas e formatos de entrega.'
    'resources\Referencias' = 'Referências mestre e critérios de uso.'
    'resources\Assets' = 'Ponto de navegação para ativos e material visual correlato.'
}

function Get-FilesInFolder {
    param([string]$Folder)
    $prefix = if ($Folder -eq '.') { '' } else { "$Folder/" }
    return $docMap.Keys | Where-Object {
        $_ -like "$prefix*" -and
        ($_.Substring($prefix.Length) -notmatch '/')
    } | Sort-Object
}

foreach ($folder in $folderDescriptions.Keys) {
    $indexPath = if ($folder -eq '.') {
        Join-Path $outputRoot 'README.md'
    } else {
        Join-Path (Join-Path $outputRoot $folder) 'INDEX.md'
    }

    if ($folder -ne '.' -and -not (Test-Path -LiteralPath (Split-Path -Parent $indexPath))) {
        New-Item -ItemType Directory -Path (Split-Path -Parent $indexPath) -Force | Out-Null
    }

    $fileEntries = @()
    foreach ($file in Get-FilesInFolder -Folder $folder) {
        if ($file -match '/INDEX\.md$') {
            continue
        }
        $cfg = $docMap[$file]
        $name = Split-Path -Leaf $file
        $encoded = [System.Uri]::EscapeDataString($name) -replace '\+', '%20'
        $relativeLink = if ($folder -eq '.') {
            ".\/$(([System.Uri]::EscapeDataString($file.Replace('\','/'))).Replace('%2F','/'))"
        } else {
            "./$encoded"
        }
        $relatedLine = ''
        if ($cfg.Related -and $cfg.Related.Count -gt 0) {
            $relatedLine = " Relacionado: " + (($cfg.Related | ForEach-Object {
                $leaf = $_.Split('/')[-1] -replace '%20', ' '
                "[$leaf]($_)"
            }) -join ', ') + '.'
        }
        $fileEntries += "- [$name]($relativeLink): $($cfg.Description)$relatedLine"
    }

    $subdirs = @()
    if ($folder -ne '.') {
        $subdirs = $folderDescriptions.Keys | Where-Object {
            $_ -ne $folder -and $_ -like "$folder\*" -and ($_.Substring($folder.Length + 1) -notmatch '\\')
        } | Sort-Object
    } else {
        $subdirs = $folderDescriptions.Keys | Where-Object { $_ -ne '.' -and ($_ -notmatch '\\') } | Sort-Object
    }

    $subdirEntries = @()
    foreach ($subdir in $subdirs) {
        $leaf = Split-Path -Leaf $subdir
        $rel = if ($folder -eq '.') { "./$($subdir.Replace('\','/'))/INDEX.md" } else { "./$leaf/INDEX.md" }
        $subdirEntries += "- [$leaf]($rel): $($folderDescriptions[$subdir])"
    }

    if ($folder -eq '.') {
        $tree = @(
            'BrandBible/',
            '├── README.md',
            '├── core/',
            '├── avatar/',
            '├── communication/',
            '├── marketing/',
            '├── design/',
            '├── library/',
            '├── product/',
            '├── sales/',
            '├── operations/',
            '├── cases/',
            '└── resources/'
        ) -join "`r`n"

        $readme = @(
            '# BrandBible',
            '',
            '## Visão geral da documentação',
            '',
            'Esta documentação reorganiza integralmente o conteúdo de `MARCAR_HORA_BRAND_BIBLE.md` em uma arquitetura modular.',
            '',
            'O material foi distribuído por domínio temático para facilitar navegação, manutenção, expansão e referência cruzada sem alterar o conteúdo original.',
            '',
            '## Árvore de diretórios',
            '',
            '```text',
            $tree,
            '```',
            '',
            '## Objetivo de cada pasta',
            ''
        )
        $readme += $subdirEntries
        $readme += @(
            '',
            '## Ordem recomendada de leitura',
            '',
            '1. [core](./core/INDEX.md)',
            '2. [avatar](./avatar/INDEX.md)',
            '3. [communication](./communication/INDEX.md)',
            '4. [marketing](./marketing/INDEX.md)',
            '5. [design](./design/INDEX.md)',
            '6. [library](./library/INDEX.md)',
            '7. [product](./product/INDEX.md)',
            '8. [sales](./sales/INDEX.md)',
            '9. [operations](./operations/INDEX.md)',
            '10. [cases](./cases/INDEX.md)',
            '11. [resources](./resources/INDEX.md)',
            '',
            '## Navegação principal',
            ''
        )
        $readme += $fileEntries
        Write-Utf8File -Path $indexPath -Text (($readme -join "`r`n").TrimEnd())
    } else {
        $title = Split-Path -Leaf $folder
        $indexLines = @(
            "# $title",
            '',
            $folderDescriptions[$folder],
            '',
            '## Arquivos',
            ''
        )
        if ($fileEntries.Count -gt 0) {
            $indexLines += $fileEntries
        } else {
            $indexLines += '- Este diretório não possui arquivos próprios além dos subdiretórios de navegação.'
        }

        if ($subdirEntries.Count -gt 0) {
            $indexLines += @(
                '',
                '## Subdiretórios',
                ''
            )
            $indexLines += $subdirEntries
        }

        Write-Utf8File -Path $indexPath -Text (($indexLines -join "`r`n").TrimEnd())
    }
}
