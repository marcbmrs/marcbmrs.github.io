<?php
declare(strict_types=1);
$activeNav = $activeNav ?? '';
?>
<nav class="app-nav" aria-label="Navegação principal">
  <a<?= $activeNav === 'inicio' ? ' class="is-active"' : '' ?> href="painel.php"><picture><source type="image/avif" srcset="assets/images/icone-inicio-128w.avif"><img src="assets/images/icone-inicio-128w.webp" width="22" height="22" alt=""></picture>Início</a>
  <a<?= $activeNav === 'orcamentos' ? ' class="is-active"' : '' ?> href="orcamentos.php"><picture><source type="image/avif" srcset="assets/images/icone-orcamentos-v2-128w.avif"><img src="assets/images/icone-orcamentos-v2-128w.webp" width="22" height="22" alt=""></picture>Orçamentos</a>
  <a<?= $activeNav === 'obras' ? ' class="is-active"' : '' ?> href="obras.php"><picture><source type="image/avif" srcset="assets/images/icone-obras-v2-128w.avif"><img src="assets/images/icone-obras-v2-128w.webp" width="22" height="22" alt=""></picture>Obras</a>
  <a<?= $activeNav === 'contratos' ? ' class="is-active"' : '' ?> href="contratos.php"><img src="assets/images/icone-contratos-128w.webp" width="22" height="22" alt="">Contratos</a>
</nav>

