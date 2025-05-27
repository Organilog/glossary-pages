<?php

// Enqueue Block
add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_script(
        'wp-glossary-nav-block',
        plugins_url('blocks/wp-glossary-nav/index.js', __FILE__),
        [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components' ],
        '1.0',
        true
    );
});


// Register the Gutenberg block for glossary navigation
add_action('init', function() {
    // Enqueue block JS in editor only
    wp_register_script(
        'wp-glossary-nav-block',
        plugins_url('blocks/wp-glossary-nav/index.js', __FILE__),
        [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components' ],
        '1.0',
        true
    );

    register_block_type_from_metadata(
        __DIR__ . '/blocks/wp-glossary-nav',
        [
            'editor_script' => 'wp-glossary-nav-block',
            'render_callback' => function($attributes) {
                // Use the same PHP as your [wp_glossary_pages_nav] shortcode, adapt for the attribute
                ob_start();
                $show_all = !empty($attributes['showAll']);
                $base_slug = (get_locale() === 'fr_FR') ? 'glossaire' : 'glossary';
                $posts = get_posts([
                    'post_type'      => 'glossary',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'fields'         => 'ids',
                ]);
                $letters_with_terms = [];
                foreach ($posts as $post_id) {
                    $title = get_the_title($post_id);
                    $first_letter = strtoupper(substr($title, 0, 1));
                    if (ctype_alpha($first_letter)) {
                        $letters_with_terms[$first_letter] = true;
                    }
                }
                echo '<div class="wp-glossary-az-nav">';
                foreach (range('A', 'Z') as $letter) {
                    $has_term = !empty($letters_with_terms[$letter]);
                    $url = home_url("/$base_slug/" . strtolower($letter) . "/");
                    if ($show_all || $has_term) {
                        echo '<a href="' . esc_url($url) . '">' . esc_html($letter) . '</a> ';
                    } else {
                        echo '<span style="opacity:.5;cursor:not-allowed;">' . esc_html($letter) . '</span> ';
                    }
                }
                echo '</div>';
                return ob_get_clean();
            }
        ]
    );
});
