<?php
add_action('add_meta_boxes', function() {
    add_meta_box('glossary_fields', esc_html__('Glossary Details', 'glossary-pages'), 'wp_glossary_pages_fields_callback', 'glossary', 'normal', 'high');
});

function wp_glossary_pages_fields_callback($post) {
    $synonym = get_post_meta($post->ID, '_wp_glossary_pages_synonym', true);
    $example = get_post_meta($post->ID, '_wp_glossary_pages_example', true);
    wp_nonce_field('wp_glossary_pages_save_fields', 'wp_glossary_pages_nonce');
    ?>
    <p>
        <label for="wp_glossary_pages_synonym"><?php esc_html_e('Synonyms', 'glossary-pages'); ?></label>
        <input type="text" name="wp_glossary_pages_synonym" id="wp_glossary_pages_synonym" class="widefat" value="<?php echo esc_attr($synonym); ?>">
    </p>
    <p>
        <label for="wp_glossary_pages_example"><?php esc_html_e('Example', 'glossary-pages'); ?></label>
        <textarea name="wp_glossary_pages_example" id="wp_glossary_pages_example" rows="2" class="widefat"><?php echo esc_textarea($example); ?></textarea>
    </p>
    <?php
}

add_action('save_post', function($post_id) {
    if (!isset($_POST['wp_glossary_pages_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wp_glossary_pages_nonce'])), 'wp_glossary_pages_save_fields')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ('glossary' !== get_post_type($post_id)) return;
    if (isset($_POST['wp_glossary_pages_synonym']))
        update_post_meta($post_id, '_wp_glossary_pages_synonym', sanitize_text_field(sanitize_text_field(wp_unslash($_POST['wp_glossary_pages_synonym']))));
    if (isset($_POST['wp_glossary_pages_example']))
        update_post_meta($post_id, '_wp_glossary_pages_example', sanitize_textarea_field(sanitize_text_field(wp_unslash($_POST['wp_glossary_pages_example']))));
});
