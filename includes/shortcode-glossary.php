<?php
// Shortcode to navigate A to Z
add_shortcode('wp_glossary_pages_nav', function($atts) {
    $atts = shortcode_atts([
        'show_all' => false, // false = do not display the link if no term exists for this letter
    ], $atts, 'wp_glossary_pages_nav');

    $base_slug = (get_locale() === 'fr_FR') ? 'glossaire' : 'glossary';

    // Get all the first letter used on the title of each term
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

    ob_start();
    echo '<div class="wp-glossary-az-nav">';
    foreach (range('A', 'Z') as $letter) {
        $has_term = !empty($letters_with_terms[$letter]);
        $url = home_url("/$base_slug/" . strtolower($letter) . "/");
        if ($atts['show_all'] || $has_term) {
            echo '<a href="' . esc_url($url) . '">' . esc_html($letter) . '</a> ';
        } else {
            // Nothing to click, to it will be grey
            echo '<span style="opacity:.5;cursor:not-allowed;">' . esc_html($letter) . '</span> ';
        }
    }
    echo '</div>';
    return ob_get_clean();
});


// Shortcode for the list
add_shortcode('wp_glossary_pages_list', function($atts) {
    ob_start();
    $atts = shortcode_atts([
        'category' => '',
    ], $atts, 'wp_glossary_pages_list');

    $args = [
        'post_type' => 'glossary',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => [],
    ];
    if ($atts['category']) {
        $args['tax_query'][] = [
            'taxonomy' => 'glossary-category',
            'field' => 'slug',
            'terms' => sanitize_title($atts['category'])
        ];
    }
    $terms = get_posts($args);

    $glossary = [];
    foreach ($terms as $post) {
        $first_letter = strtoupper(substr($post->post_title, 0, 1));
        if (!isset($glossary[$first_letter])) $glossary[$first_letter] = [];
        $glossary[$first_letter][] = $post;
    }

    echo '<div class="wp-glossary-list">';
    foreach (range('A', 'Z') as $letter) {
        if (empty($glossary[$letter])) continue;
        echo '<h2 id="glossary-' . esc_html($letter) . '">' . esc_html($letter) . '</h2><ul>';
        foreach ($glossary[$letter] as $post) {
            $excerpt = has_excerpt($post) ? get_the_excerpt($post) : '';
            echo '<li><a href="' . esc_url($link) . '">' . esc_html($post->post_title) . '</a>';
            if ($excerpt) {
                echo ': ' . esc_html($excerpt);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
    echo '</div>';

    return ob_get_clean();
});

// Shortcode for the navigation
add_shortcode('wp_glossary_pages_categories', function($atts) {
    $atts = shortcode_atts([
        'show_count' => false, // show the number of terme (optional)
    ], $atts, 'wp_glossary_pages_categories');

    $terms = get_terms([
        'taxonomy' => 'glossary-category',
        'hide_empty' => true,
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return '<p>' . esc_html__('No categories found.', 'glossary-pages') . '</p>';
    }

    ob_start();
    echo '<ul class="wp-glossary-category-menu">';
    foreach ($terms as $term) {
        echo '<li><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
        if ($atts['show_count']) {
            echo ' <span>(' . intval($term->count) . ')</span>';
        }
        echo '</li>';
    }
    echo '</ul>';
    return ob_get_clean();
});
