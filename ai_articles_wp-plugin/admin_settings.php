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

        <!-- Add New Article Button -->
        <button id="open-modal" class="button button-primary">Add New Article</button>

        <!-- Modal for Adding New Article -->
        <div id="article-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add New Article</h2>
                <form id="new-article-form">
                    <label for="article-url">Article URL:</label>
                    <input type="url" id="article-url" name="article-url" class="regular-text" required>
                    
                    <label for="language">Target Language:</label>
                    <input type="text" id="language" name="language" value="<?php echo esc_attr($settings['target_language']); ?>" class="regular-text" required>

                    <button type="submit" class="button button-primary">Generate Article</button>
                </form>
            </div>
        </div>

        <!-- List of Generated Articles -->
        <h2>Generated Articles</h2>
        <table class="widefat fixed" id="articles-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>SEO</th>
                    <th>Post Link</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch generated articles (placeholder data for now)
                $articles = get_option('ai_articles_wp_generated', []); // Replace with your actual database logic

                if (!empty($articles)) {
                    foreach ($articles as $article) {
                        echo '<tr>';
                        echo '<td>' . esc_html($article['title']) . '</td>';
                        echo '<td>' . esc_html($article['status']) . '</td>';
                        echo '<td>' . esc_html($article['seo']) . '</td>';
                        echo '<td><a href="' . esc_url($article['post_link']) . '" target="_blank">View Post</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No articles generated yet.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
