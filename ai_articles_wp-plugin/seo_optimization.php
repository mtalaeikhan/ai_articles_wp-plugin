<?php

function optimize_for_seo($content) {
    // Check if the content is valid
    if (empty($content['title']) || empty($content['text'])) {
        return new WP_Error('invalid_content', 'Content must include both title and text for SEO optimization.');
    }

    // Check if Yoast SEO is active
    if (defined('WPSEO_VERSION')) {
        // Use Yoast SEO to optimize content
        $yoast_data = [
            '_yoast_wpseo_focuskw' => implode(', ', $content['keywords']), // Focus keywords
            '_yoast_wpseo_title' => $content['title'], // SEO title
            '_yoast_wpseo_metadesc' => substr($content['text'], 0, 156), // Meta description (first 156 characters)
        ];

        // Apply Yoast SEO data to the post
        add_filter('wp_insert_post_data', function ($data) use ($yoast_data) {
            foreach ($yoast_data as $key => $value) {
                update_post_meta($data['ID'], $key, $value);
            }
            return $data;
        });

        return true;
    } else {
        // Fallback: Basic SEO optimization without Yoast
        $basic_seo_data = [
            'title' => $content['title'],
            'meta_description' => substr($content['text'], 0, 156),
        ];

        // Return basic SEO data for further processing
        return $basic_seo_data;
    }
}
