<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wp_head', function() {
    if (is_singular('glospa-glossary')) {
        global $post;
        $definition = get_post_meta($post->ID, '_glossary_pages_definition', true);
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "DefinedTerm",
          "name": "<?php echo esc_js(get_the_title($post)); ?>",
          "description": "<?php echo esc_js($definition); ?>",
          "inDefinedTermSet": "<?php echo esc_url(get_post_type_archive_link('glospa-glossary')); ?>"
        }
        </script>
        <?php
    }
});
