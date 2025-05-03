<?php

// Add the plugin settings menu to the WordPress admin dashboard
function ai_articles_wp_admin_menu() {
    add_menu_page(
        'AI Articles WP Settings', // Page title
        'AI Articles WP', // Menu title
        'manage_options', // Capability
        'ai-articles-wp-settings', // Menu slug
        'ai_articles_wp_settings_page', // Callback function
        'dashicons-translation', // Icon
        100 // Menu position
    );
}
add_action('admin_menu', 'ai_articles_wp_admin_menu');

// Render the settings page HTML
function ai_articles_wp_settings_page() {
    // Check if the user has permission to manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if the form is submitted
    if (isset($_POST['ai_articles_wp_save_settings'])) {
        // Sanitize and save settings
        update_option('ai_articles_wp_settings', [
            'default_language' => sanitize_text_field($_POST['default_language']),
            'target_language' => sanitize_text_field($_POST['target_language']),
            'enable_seo' => isset($_POST['enable_seo']) ? 1 : 0,
            'api_key' => sanitize_text_field($_POST['api_key']),
        ]);

        // Display an admin notice
        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }

    // Retrieve current settings
    $settings = get_option('ai_articles_wp_settings', [
        'default_language' => 'en',
        'target_language' => 'fa',
        'enable_seo' => 1,
        'api_key' => '',
    ]);

    // Render the settings form
    ?>
    <div class="wrap">
        <h1>AI Articles WP Plugin Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="default_language">Default Language</label></th>
                    <td>
                        <input type="text" name="default_language" id="default_language" 
                               value="<?php echo esc_attr($settings['default_language']); ?>" 
                               class="regular-text">
                        <p class="description">Enter the default language of the articles (e.g., en for English).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="target_language">Target Language</label></th>
                    <td>
                        <input type="text" name="target_language" id="target_language" 
                               value="<?php echo esc_attr($settings['target_language']); ?>" 
                               class="regular-text">
                        <p class="description">Enter the target language for translation (e.g., fa for Farsi).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_seo">Enable SEO Optimization</label></th>
                    <td>
                        <input type="checkbox" name="enable_seo" id="enable_seo" 
                               value="1" <?php checked($settings['enable_seo'], 1); ?>>
                        <label for="enable_seo">Enable integration with Yoast SEO or basic SEO optimization.</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="api_key">API Key</label></th>
                    <td>
                        <input type="text" name="api_key" id="api_key" 
                               value="<?php echo esc_attr($settings['api_key']); ?>" 
                               class="regular-text">
                        <p class="description">Enter your API key for the Hugging Face translation service.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'ai_articles_wp_save_settings'); ?>
        </form>
    </div>
    <?php
}
