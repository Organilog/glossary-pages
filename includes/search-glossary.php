<?php
add_shortcode('wp_glossary_pages_search', function() {
    ob_start();
    ?>
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="wp-glossary-search-form">
        <input type="text" name="s" placeholder="<?php esc_html_e('Search the glossary...', 'glossary-pages'); ?>" value="<?php echo get_search_query(); ?>">
        <input type="hidden" name="post_type" value="glossary">
        <button type="submit"><?php esc_html_e('Search', 'glossary-pages'); ?></button>
    </form>
    <?php
    return ob_get_clean();
});
