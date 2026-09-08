<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#conteudo">Pular para o conteúdo</a>
<header class="dmb-header"><div class="dmb-container dmb-header__inner"><div class="dmb-brand"><?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?><a class="dmb-brand__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" tabindex="-1" aria-hidden="true"><img class="dmb-brand__logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-dmb.webp' ) ); ?>" width="320" height="160" alt=""></a><?php } ?><a class="dmb-brand__copy" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Blog DMB Psicologia, início"><strong>DMB Psicologia</strong><small>e Desenvolvimento Humano</small></a></div><nav aria-label="Navegação principal"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'dmb-menu', 'fallback_cb' => 'dmb_blog_menu_fallback' ) ); ?></nav></div></header>
<main id="conteudo">
