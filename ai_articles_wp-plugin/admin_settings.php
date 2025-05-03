<?php

function ai_articles_wp_admin_menu() {
    add_menu_page(
        'AI Articles WP Settings',
        'AI Articles WP',
        'manage_options',
        'ai-articles-wp-settings',
        'ai_articles_wp_settings_page'
    );
}
add_action('admin_menu', 'ai_articles_wp_admin_menu');

function ai_articles_wp_settings_page() {
    echo '<h1>AI Articles WP Plugin Settings</h1>';
    // Add settings form here
}
