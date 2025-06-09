<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('add_meta_boxes', function() {
    add_meta_box('glossary_fields', esc_html__('Glossary Details', 'glossary-pages'), 'glospa_fields_callback', 'glospa-glossary', 'normal', 'high');
});

function glospa_fields_callback($post) {
    $synonym = get_post_meta($post->ID, '_glossary_pages_synonym', true);
    $example = get_post_meta($post->ID, '_glossary_pages_example', true);
    wp_nonce_field('glossary_pages_save_fields', 'glossary_pages_nonce');
    ?>
    <p>
        <label for="glossary_pages_synonym"><?php esc_html_e('Synonyms', 'glossary-pages'); ?></label>
        <input type="text" name="glossary_pages_synonym" id="glossary_pages_synonym" class="widefat" value="<?php echo esc_attr($synonym); ?>">
    </p>
    <p>
        <label for="glossary_pages_example"><?php esc_html_e('Example', 'glossary-pages'); ?></label>
        <textarea name="glossary_pages_example" id="glossary_pages_example" rows="2" class="widefat"><?php echo esc_textarea($example); ?></textarea>
    </p>
    <?php
}

add_action('save_post', function($post_id) {
    if (!isset($_POST['glossary_pages_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['glossary_pages_nonce'])), 'glossary_pages_save_fields')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ('glospa-glossary' !== get_post_type($post_id)) return;
    if (isset($_POST['glossary_pages_synonym']))
        update_post_meta($post_id, '_glossary_pages_synonym', sanitize_text_field(sanitize_text_field(wp_unslash($_POST['glossary_pages_synonym']))));
    if (isset($_POST['glossary_pages_example']))
        update_post_meta($post_id, '_glossary_pages_example', sanitize_textarea_field(sanitize_text_field(wp_unslash($_POST['glossary_pages_example']))));
});
