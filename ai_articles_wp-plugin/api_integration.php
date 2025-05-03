<?php

function translate_content($content, $target_language = 'fa') {
    // Check if content is valid
    if (empty($content['text']) || empty($content['title'])) {
        return new WP_Error('invalid_content', 'Content must include both text and title for translation.');
    }

    // API Endpoint and Key (Replace with your Hugging Face API details)
    $api_url = 'https://api-inference.huggingface.co/models/Helsinki-NLP/opus-mt-en-' . $target_language;
    $api_key = 'your-hugging-face-api-key'; // Replace with your actual API key

    // Prepare headers
    $headers = [
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json',
    ];

    // Prepare request body
    $body = json_encode([
        'inputs' => $content['text'],
    ]);

    // Make API request
    $response = wp_remote_post($api_url, [
        'headers' => $headers,
        'body' => $body,
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_request_failed', 'Failed to connect to the translation API.');
    }

    // Parse API response
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);

    if (empty($response_data) || isset($response_data['error'])) {
        return new WP_Error('api_error', 'Error in API response: ' . ($response_data['error'] ?? 'Unknown error.'));
    }

    // Extract translated text
    $translated_text = $response_data[0]['translation_text'];

    // Return translated content
    return [
        'title' => $content['title'], // Title translation can be implemented separately if needed
        'text' => $translated_text,
    ];
}
