<?php
declare(strict_types=1); require __DIR__ . '/../app/db.php'; $pdo=banco();
$dados=[
'ORC-DEMO-001'=>[['mao_de_obra','Chapisco + reboco','m²',45,null,null,8000],['equipamento','Betoneira 400 L','diária',1,3,'diaria',9000]],
'ORC-DEMO-002'=>[['mao_de_obra','Assentamento de porcelanato','m²',80,null,null,9500],['mao_de_obra','Rodapé embutido','metro linear',42,null,null,6000],['equipamento','Cortadora de piso','diária',1,5,'diaria',12000]],
'ORC-DEMO-003'=>[['mao_de_obra','Construção de muro','m²',38,null,null,15000],['mao_de_obra','Concretagem de piso','m²',24,null,null,7000],['equipamento','Compactador de solo','diária',1,2,'diaria',22000]],
'ORC-DEMO-004'=>[['mao_de_obra','Demolição de parede','m²',12,null,null,7000],['mao_de_obra','Retirada de revestimento','m²',18,null,null,5000],['equipamento','Rompedor 15 kg','diária',1,2,'diaria',12000]],
'ORC-DEMO-005'=>[['mao_de_obra','Revestimento de banheiro','m²',36,null,null,10000],['mao_de_obra','Impermeabilização de banheiro','m²',18,null,null,13000],['mao_de_obra','Construção de nicho de banheiro','unidade',2,null,null,25000],['equipamento','Cortadora de piso','diária',1,4,'diaria',12000]],
'ORC-DEMO-006'=>[['mao_de_obra','Contrapiso','m²',52,null,null,5000],['mao_de_obra','Regularização de piso','m²',52,null,null,4500],['equipamento','Betoneira 400 L','diária',1,2,'diaria',9000]],
'ORC-DEMO-007'=>[['mao_de_obra','Construção de calçada','m²',95,null,null,8000],['mao_de_obra','Piso de concreto','m²',95,null,null,8000],['equipamento','Placa vibratória','diária',1,3,'diaria',20000]],
'ORC-DEMO-008'=>[['mao_de_obra','Escada de alvenaria','degrau',14,null,null,15000],['mao_de_obra','Vergas/contravergas','metro',18,null,null,7000],['equipamento','Andaime tubular','peça',12,null,'peca',1000]],
];
$get=$pdo->prepare('SELECT id FROM orcamentos WHERE codigo=?'); $del=$pdo->prepare('DELETE FROM itens_orcamento WHERE orcamento_id=?'); $ins=$pdo->prepare('INSERT INTO itens_orcamento (orcamento_id,categoria,descricao,unidade,quantidade,periodo_quantidade,periodo_tipo,valor_tabela_centavos,valor_unitario_centavos,subtotal_centavos) VALUES (?,?,?,?,?,?,?,?,?,?)'); $upd=$pdo->prepare('UPDATE orcamentos SET total_centavos=? WHERE id=?');
$pdo->beginTransaction(); foreach($dados as $codigo=>$itens){$get->execute([$codigo]);$id=(int)$get->fetchColumn();if(!$id)continue;$del->execute([$id]);$total=0;foreach($itens as [$cat,$desc,$un,$qtd,$periodo,$tipo,$valor]){$sub=$qtd*($periodo??1)*$valor;$total+=$sub;$ins->execute([$id,$cat,$desc,$un,$qtd,$periodo,$tipo,$valor,$valor,$sub]);}$upd->execute([$total,$id]);} $pdo->commit(); echo 'Itens demonstrativos atualizados.';
