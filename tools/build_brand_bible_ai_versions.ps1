$ErrorActionPreference = 'Stop'

$root = 'C:\marcbmrs.github.io'
$brandBibleRoot = Join-Path $root 'BrandBible'

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

function Get-BlocksFromFile {
    param(
        [string]$Path
    )

    $content = Get-Content -LiteralPath $Path -Raw -Encoding UTF8
    $lines = $content -split "`r?`n"

    $starts = @()
    for ($i = 0; $i -lt $lines.Count; $i++) {
        if ($lines[$i] -match '^# ') {
            $starts += $i
        }
    }

    if ($starts.Count -eq 0) {
        return ,([pscustomobject]@{
            Title = Split-Path -LeafBase $Path
            Text  = $content.TrimEnd()
        })
    }

    $blocks = @()
    for ($i = 0; $i -lt $starts.Count; $i++) {
        $start = $starts[$i]
        $end = if ($i -lt $starts.Count - 1) { $starts[$i + 1] - 1 } else { $lines.Count - 1 }
        $title = $lines[$start] -replace '^# ', ''
        $text = ($lines[$start..$end] -join "`r`n").TrimEnd()
        $blocks += [pscustomobject]@{
            Title = $title
            Text  = $text
        }
    }

    return $blocks
}

function Normalize-Block {
    param([string]$Text)

    return (($Text -replace '\s+', ' ').Trim()).ToLowerInvariant()
}

function Build-GptDocument {
    param(
        [string]$OutputName,
        [hashtable[]]$Groups
    )

    $seen = @{}
    $parts = @()
    $parts += "# $OutputName"
    $parts += ''
    $parts += '## Mapa rápido'
    $parts += ''

    foreach ($group in $Groups) {
        $parts += "- $($group.Title)"
    }

    foreach ($group in $Groups) {
        $parts += ''
        $parts += "## $($group.Title)"
        $parts += ''

        foreach ($relativePath in $group.Files) {
            $fullPath = Join-Path $brandBibleRoot $relativePath
            if (-not (Test-Path -LiteralPath $fullPath)) {
                throw "Arquivo não encontrado: $fullPath"
            }

            foreach ($block in Get-BlocksFromFile -Path $fullPath) {
                $key = Normalize-Block -Text $block.Text
                if (-not $seen.ContainsKey($key)) {
                    $parts += $block.Text
                    $parts += ''
                    $seen[$key] = $true
                }
            }
        }
    }

    $outputPath = Join-Path $brandBibleRoot $OutputName
    Write-Utf8File -Path $outputPath -Text (($parts -join "`r`n").TrimEnd())
}

$documents = @(
    @{
        Output = 'Core_GPT.md'
        Groups = @(
            @{ Title = 'Filosofia e identidade'; Files = @('core/Filosofia.md', 'core/Posicionamento.md') }
            @{ Title = 'Promessa, crença e direcionamento'; Files = @('core/Promessa.md', 'core/Crenca.md') }
            @{ Title = 'Estrutura institucional'; Files = @('core/Missao.md', 'core/Visao.md', 'core/Valores.md') }
            @{ Title = 'Regras fundamentais'; Files = @('core/Regras Fundamentais.md') }
        )
    }
    @{
        Output = 'Avatar_GPT.md'
        Groups = @(
            @{ Title = 'Perfil e contexto'; Files = @('avatar/Avatar Principal.md') }
            @{ Title = 'Dores e tensões'; Files = @('avatar/Dores.md') }
            @{ Title = 'Desejos e objeções'; Files = @('avatar/Sonhos.md', 'avatar/Objecoes.md') }
            @{ Title = 'Linguagem'; Files = @('avatar/Linguagem.md') }
        )
    }
    @{
        Output = 'Comunicacao_GPT.md'
        Groups = @(
            @{ Title = 'Tom, voz e escrita'; Files = @('communication/Tom de Voz.md') }
            @{ Title = 'Copy e estrutura de texto'; Files = @('communication/Copywriting.md', 'communication/Storytelling.md') }
            @{ Title = 'Psicologia da comunicação'; Files = @('communication/Psicologia do Conteudo.md') }
            @{ Title = 'Chamadas e finalização'; Files = @('communication/CTA.md', 'resources/Templates/Legendas.md') }
        )
    }
    @{
        Output = 'Marketing_GPT.md'
        Groups = @(
            @{ Title = 'Sistema editorial e decisão'; Files = @('marketing/Calendario Editorial.md', 'marketing/Conteudo.md', 'marketing/Frameworks.md') }
            @{ Title = 'Carrosséis'; Files = @('marketing/Carrosseis.md') }
            @{ Title = 'Reels'; Files = @('marketing/Reels.md') }
            @{ Title = 'Stories'; Files = @('marketing/Stories.md') }
            @{ Title = 'Landing pages e campanhas'; Files = @('marketing/Landing Pages.md', 'cases/Campanhas/INDEX.md') }
        )
    }
    @{
        Output = 'Design_GPT.md'
        Groups = @(
            @{ Title = 'Direção de arte e identidade visual'; Files = @('design/Direcao de Arte.md') }
            @{ Title = 'Composição e sistema visual'; Files = @('design/Hierarquia Visual.md', 'design/Tipografia.md', 'design/Espacamento.md', 'design/Cores.md', 'design/Componentes.md') }
            @{ Title = 'Contexto de uso visual'; Files = @('design/Smartphone.md', 'design/Fotografia.md', 'design/Mockups.md') }
            @{ Title = 'Prompts e avaliação visual'; Files = @('resources/Prompts/Prompts Visuais.md', 'resources/Prompts/Avaliacao de Arte.md') }
        )
    }
    @{
        Output = 'Produto_GPT.md'
        Groups = @(
            @{ Title = 'Posicionamento do produto'; Files = @('product/Produto.md') }
            @{ Title = 'Funcionalidades e benefícios'; Files = @('product/Funcionalidades.md', 'product/Beneficios.md') }
            @{ Title = 'Integrações, limitações e roadmap'; Files = @('product/Integracoes.md', 'product/Limitacoes.md', 'product/Roadmap.md') }
        )
    }
    @{
        Output = 'Comercial_GPT.md'
        Groups = @(
            @{ Title = 'Prospecção e abordagem'; Files = @('sales/Prospecção.md', 'sales/Direct.md') }
            @{ Title = 'Diagnóstico e apresentação'; Files = @('sales/Diagnóstico.md', 'sales/Apresentação.md') }
            @{ Title = 'Objeções, follow-up e encerramento'; Files = @('sales/Objeções.md', 'sales/Follow-up.md', 'sales/Encerramento.md') }
            @{ Title = 'Análise de conversas'; Files = @('sales/Conversas Reais.md') }
        )
    }
    @{
        Output = 'Sistema_GPT.md'
        Groups = @(
            @{ Title = 'Sistema operacional'; Files = @('operations/Sistema Operacional.md') }
            @{ Title = 'Processo criativo e decisão'; Files = @('operations/Processo Criativo.md', 'operations/Processo de Decisao.md') }
            @{ Title = 'Fluxos e checklists'; Files = @('operations/Fluxos.md', 'operations/Checklist.md') }
        )
    }
    @{
        Output = 'Bibliotecas_GPT.md'
        Groups = @(
            @{ Title = 'Ganchos, headlines e perguntas'; Files = @('library/Ganchos.md', 'library/Headlines.md', 'library/Perguntas.md') }
            @{ Title = 'Dores, observações, analogias e crenças'; Files = @('library/Dores.md', 'library/Observacoes.md', 'library/Analogias.md', 'library/Crencas.md') }
            @{ Title = 'CTA, objeções e FAQ'; Files = @('library/CTA.md', 'library/Objeções.md', 'library/FAQ.md', 'resources/Templates/Respostas Publicas.md') }
        )
    }
    @{
        Output = 'CasosReais_GPT.md'
        Groups = @(
            @{ Title = 'Casos aprovados'; Files = @('cases/Carrosseis/Aprovados.md', 'cases/Stories/Aprovados.md', 'cases/Reels/Aprovados.md', 'resources/Referencias/Exemplos Aprovados.md') }
        )
    }
)

foreach ($document in $documents) {
    Build-GptDocument -OutputName $document.Output -Groups $document.Groups
}
