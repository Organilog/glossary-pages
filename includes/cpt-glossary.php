<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', function() {
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

    $slug = ( strpos(get_locale(), 'fr_') === 0 ) ? 'glossaire' : 'glossary';

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => $slug ],
        'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-book',
        'taxonomies'         => [ 'glospa-glossary-category' ],
    ];

    register_post_type( 'glospa-glossary', $args );
});

add_action('init', function() {
    $labels = [
        'name'              => esc_html__( 'Term Categories', 'glossary-pages' ),
        'singular_name'     => esc_html__( 'Term Category', 'glossary-pages' ),
        'search_items'      => esc_html__( 'Search Categories', 'glossary-pages' ),
        'all_items'         => esc_html__( 'All Categories', 'glossary-pages' ),
        'parent_item'       => esc_html__( 'Parent Category', 'glossary-pages' ),
        'edit_item'         => esc_html__( 'Edit Category', 'glossary-pages' ),
        'update_item'       => esc_html__( 'Update Category', 'glossary-pages' ),
        'add_new_item'      => esc_html__( 'Add New Category', 'glossary-pages' ),
        'new_item_name'     => esc_html__( 'New Category Name', 'glossary-pages' ),
        'menu_name'         => esc_html__( 'Categories', 'glossary-pages' ),
    ];
    register_taxonomy(
        'glospa-glossary-category',
        'glospa-glossary',
        [
            'hierarchical' => True,
            'labels' => $labels,
            'show_ui' => True,
            'show_in_rest' => True,
            'rewrite' => [ 'slug' => 'glossary-category' ],
        ]
    );
});
