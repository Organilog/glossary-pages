<?php
add_action('wp_head', function() {
    if (is_singular('glossary')) {
        global $post;
        $definition = get_post_meta($post->ID, '_wp_glossary_pages_definition', true);
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "DefinedTerm",
          "name": "<?php echo esc_js(get_the_title($post)); ?>",
          "description": "<?php echo esc_js($definition); ?>",
          "inDefinedTermSet": "<?php echo esc_url(get_post_type_archive_link('glossary')); ?>"
        }
        </script>
        <?php
    }
});
