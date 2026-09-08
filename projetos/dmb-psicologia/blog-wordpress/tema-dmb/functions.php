<?php
/** Funções do tema DMB Blog. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function dmb_blog_setup() {
	load_theme_textdomain( 'dmb-blog', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 180, 'width' => 380, 'flex-height' => true, 'flex-width' => true ) );
	register_nav_menus( array( 'primary' => __( 'Menu principal', 'dmb-blog' ) ) );
}
add_action( 'after_setup_theme', 'dmb_blog_setup' );

require get_template_directory() . '/inc/menu.php';

function dmb_blog_assets() {
	wp_enqueue_style( 'dmb-blog-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap', array(), null );
	wp_enqueue_style( 'dmb-blog-style', get_stylesheet_uri(), array( 'dmb-blog-fonts' ), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'dmb_blog_assets' );

function dmb_blog_excerpt_length( $length ) { return 24; }
add_filter( 'excerpt_length', 'dmb_blog_excerpt_length', 999 );
