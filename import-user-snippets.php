<?php
$json_file = 'Disable Email Required Field For User Imports.json';
if (!file_exists($json_file)) {
    WP_CLI::error("JSON file not found: $json_file");
}

$snippets = json_decode(file_get_contents($json_file), true);

if (is_array($snippets)) {
    foreach ($snippets as $snippet) {
        $post_id = wp_insert_post([
            'post_title'   => $snippet['name'],
            'post_content' => $snippet['code'],
            'post_type'    => 'snippets',
            'post_status'  => 'publish',
        ]);

        if (is_wp_error($post_id)) {
            WP_CLI::error("Failed to import snippet: " . $snippet['name']);
        } else {
            WP_CLI::success("Imported snippet: " . $snippet['name']);
        }
    }
} else {
    WP_CLI::error("Invalid JSON format.");
}