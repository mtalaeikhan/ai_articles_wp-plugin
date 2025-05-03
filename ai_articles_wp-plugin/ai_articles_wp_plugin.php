<?php
/**
 * Plugin Name: AI Articles WP Plugin
 * Description: A plugin to fetch articles from URLs, translate them using Hugging Face API, optimize SEO with Yoast, and save them as WordPress drafts.
 * Version: 1.0
 * Author: Your Name
 */

defined('ABSPATH') || exit;

// Include plugin files
require_once plugin_dir_path(__FILE__) . 'content_extraction.php';
require_once plugin_dir_path(__FILE__) . 'api_integration.php';
require_once plugin_dir_path(__FILE__) . 'seo_optimization.php';
require_once plugin_dir_path(__FILE__) . 'admin_settings.php';
require_once plugin_dir_path(__FILE__) . 'post_handling.php';

// Activation hook
function ai_articles_wp_activate() {
    // Actions to perform on plugin activation
    add_option('ai_articles_wp_settings', [
        'default_language' => 'en',
        'target_language' => 'fa',
        'enable_seo' => true,
    ]);
}
register_activation_hook(__FILE__, 'ai_articles_wp_activate');

// Deactivation hook
function ai_articles_wp_deactivate() {
    // Actions to perform on plugin deactivation
    delete_option('ai_articles_wp_settings');
}
register_deactivation_hook(__FILE__, 'ai_articles_wp_deactivate');

// Initialize plugin
function ai_articles_wp_init() {
    // Load text domain for translations
    load_plugin_textdomain('ai-articles-wp', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Enqueue admin scripts and styles
    add_action('admin_enqueue_scripts', 'ai_articles_wp_enqueue_admin_scripts');
}
add_action('plugins_loaded', 'ai_articles_wp_init');

// Enqueue admin scripts and styles
function ai_articles_wp_enqueue_admin_scripts() {
    wp_enqueue_style('ai-articles-wp-admin-style', plugin_dir_url(__FILE__) . 'assets/admin-style.css');
    wp_enqueue_script('ai-articles-wp-admin-script', plugin_dir_url(__FILE__) . 'assets/admin-script.js', ['jquery'], null, true);
}
