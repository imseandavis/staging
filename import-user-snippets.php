<?php
$json_file = 'ignore-email-field.json'; 

if (!file_exists($json_file)) {
    WP_CLI::error("JSON file not found: $json_file");
}

$snippets = json_decode(file_get_contents($json_file), true);

if (is_array($snippets)) {
    foreach ($snippets as $snippet) {
        // Extract relevant keys from the JSON
        $title = isset($snippet['title']) ? $snippet['title'] : 'Unnamed Snippet';
        $code = isset($snippet['code']) ? $snippet['code'] : '';
        $location = isset($snippet['location']) ? $snippet['location'] : 'everywhere';
        $note = isset($snippet['note']) ? $snippet['note'] : '';

        // Insert the snippet as a custom post
        $post_id = wp_insert_post([
            'post_title'   => $title,
            'post_content' => $code,
            'post_type'    => 'snippets',
            'post_status'  => 'publish',
            'post_excerpt' => $note, // Optional: store note in the excerpt
        ]);

        // Add custom meta for additional fields
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, 'location', $location);
            update_post_meta($post_id, 'priority', $snippet['priority']);
            update_post_meta($post_id, 'auto_insert', $snippet['auto_insert']);
            WP_CLI::success("Imported snippet: $title");
        } else {
            WP_CLI::error("Failed to import snippet: $title");
        }
    }
} else {
    WP_CLI::error("Invalid JSON format.");
}
