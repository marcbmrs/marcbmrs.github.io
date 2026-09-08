<?php
/** Menu utilizado enquanto o menu do WordPress ainda não foi configurado. */
function dmb_blog_menu_fallback() {
	echo '<ul class="dmb-menu"><li><a href="' . esc_url( home_url( '/' ) ) . '">Conteúdos</a></li><li><a href="https://dmbpsicologia.com.br/">Conheça a DMB</a></li><li class="menu-item--contact"><a href="https://wa.me/5551995765972">Agendar conversa</a></li></ul>';
}
