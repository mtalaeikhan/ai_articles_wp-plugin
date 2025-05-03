<?php

function save_content_as_draft($content) {
    $post_id = wp_insert_post([
        'post_title' => $content['title'],
        'post_content' => $content['text'],
        'post_status' => 'draft',
        'post_type' => 'post',
    ]);
    return $post_id;
}
