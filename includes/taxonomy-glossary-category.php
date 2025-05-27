<?php
add_action( 'init', function() {
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
        'glossary-category',
        'glossary',
        [
            'hierarchical' => True,
            'labels' => $labels,
            'show_ui' => True,
            'show_in_rest' => True,
            'rewrite' => [ 'slug' => 'glossary-category' ],
        ]
    );
});
