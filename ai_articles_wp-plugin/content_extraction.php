<?php

function extract_content_from_url($url) {
    // Validate the URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return new WP_Error('invalid_url', 'The provided URL is not valid.');
    }

    // Fetch the content from the URL using cURL
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return new WP_Error('fetch_failed', 'Failed to fetch the URL content.');
    }

    $html = wp_remote_retrieve_body($response);

    // Check if content is retrieved
    if (empty($html)) {
        return new WP_Error('no_content', 'No content found at the provided URL.');
    }

    // Use DOMDocument to parse the HTML content
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Extract the title
    $title = '';
    $titleTags = $dom->getElementsByTagName('title');
    if ($titleTags->length > 0) {
        $title = $titleTags->item(0)->textContent;
    }

    // Extract meta keywords
    $keywords = '';
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $meta) {
        if ($meta->getAttribute('name') === 'keywords') {
            $keywords = $meta->getAttribute('content');
            break;
        }
    }

    // Extract main content text (basic heuristic)
    $body = $dom->getElementsByTagName('body');
    $textContent = '';
    if ($body->length > 0) {
        $textContent = strip_tags($body->item(0)->textContent);
    }

    // Extract images (if any)
    $images = [];
    $imageTags = $dom->getElementsByTagName('img');
    foreach ($imageTags as $img) {
        $src = $img->getAttribute('src');
        if (!empty($src)) {
            $images[] = $src;
        }
    }

    // Return extracted content as an array
    return [
        'title' => $title,
        'keywords' => array_map('trim', explode(',', $keywords)),
        'text' => $textContent,
        'images' => $images,
    ];
}
