<?php

function save_content_as_draft($content) {
    // Validate the content
    if (empty($content['title']) || empty($content['text'])) {
        return new WP_Error('invalid_content', 'Content must include both a title and text.');
    }

    // Prepare post data
    $post_data = [
        'post_title' => sanitize_text_field($content['title']),
        'post_content' => wp_kses_post($content['text']),
        'post_status' => 'draft',
        'post_type' => 'post',
    ];

    // Save post as draft
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('save_failed', 'Failed to save the content as a draft.');
    }

    // Attach images to the post
    if (!empty($content['images'])) {
        foreach ($content['images'] as $image_url) {
            attach_image_to_post($image_url, $post_id);
        }
    }

    // Return the post ID
    return $post_id;
}

function attach_image_to_post($image_url, $post_id) {
    // Download the image
    $tmp = download_url($image_url);

    if (is_wp_error($tmp)) {
        return new WP_Error('image_download_failed', 'Failed to download the image: ' . $image_url);
    }

    // Get the image name
    $file_array = [
        'name' => basename($image_url),
        'tmp_name' => $tmp,
    ];

    // Upload the image to the WordPress media library
    $attachment_id = media_handle_sideload($file_array, $post_id);

    // Handle any upload errors
    if (is_wp_error($attachment_id)) {
        @unlink($tmp); // Remove the temporary file
        return new WP_Error('image_upload_failed', 'Failed to upload the image: ' . $image_url);
    }

    // Attach the image to the post
    set_post_thumbnail($post_id, $attachment_id);
}
