<?php
// Rejestracja menu
function asprojekt_setup() {
    register_nav_menus([
        'primary' => 'Menu główne',
    ]);
}
add_action('after_setup_theme', 'asprojekt_setup');

// Ładowanie CSS motywu
function asprojekt_enqueue_styles() {
    wp_enqueue_style('asprojekt-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'asprojekt_enqueue_styles');
