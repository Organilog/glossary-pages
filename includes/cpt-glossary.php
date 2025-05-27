<?php
add_action( 'init', function() {
    $lang = get_locale();
    $labels = [
        'name'               => esc_html__( 'Glossary', 'glossary-pages' ),
        'singular_name'      => esc_html__( 'Term', 'glossary-pages' ),
        'add_new'            => esc_html__( 'Add New', 'glossary-pages' ),
        'add_new_item'       => esc_html__( 'Add New Term', 'glossary-pages' ),
        'edit_item'          => esc_html__( 'Edit Term', 'glossary-pages' ),
        'new_item'           => esc_html__( 'New Term', 'glossary-pages' ),
        'view_item'          => esc_html__( 'View Term', 'glossary-pages' ),
        'search_items'       => esc_html__( 'Search Terms', 'glossary-pages' ),
        'not_found'          => esc_html__( 'No terms found', 'glossary-pages' ),
        'not_found_in_trash' => esc_html__( 'No terms found in Trash', 'glossary-pages' ),
        'menu_name'          => esc_html__( 'Glossary', 'glossary-pages' ),
    ];

    $slug = ( strpos($lang, 'fr_') === 0 ) ? 'glossaire' : 'glossary';

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => $slug ],
        'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-book',
        'taxonomies'         => [ 'glossary-category' ],
    ];

    register_post_type( 'glossary', $args );
} );
