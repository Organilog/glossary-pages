<?php
/*
Plugin Name: Glossary Pages
Plugin URI:  https://github.com/Organilog
Description: A customizable glossary plugin with custom post types (1 page per term), categories, A-Z navigation, and WPML/Polylang support.
Version:     1.2.0
Author:      Organilog
Author URI:  https://fr.organilog.com/
License:     GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: glossary-pages
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/***************************************************************
 * Install and uninstall
 ***************************************************************/


// Activation/deactivation/uninstall
if ( function_exists( 'register_activation_hook' ) ) {
    register_activation_hook( __FILE__, 'wp_glossary_pages_install' );
}
if ( function_exists( 'register_deactivation_hook' ) ) {
    register_deactivation_hook( __FILE__, 'wp_glossary_pages_deactivate' );
}
if ( function_exists( 'register_uninstall_hook' ) ) {
    register_uninstall_hook( __FILE__, 'wp_glossary_pages_uninstall' );
}

function wp_glossary_pages_pages_install() {
    add_option( 'wp_glossary_pages_flush_needed', 1 );
}
function wp_glossary_pages_pages_deactivate() {
    // nothing but do
}
function wp_glossary_pages_pages_uninstall() {
    // nothing but do
}


/***************************************************************
 * URL
 ***************************************************************/


// Automatic Flush after init, if necessary
add_action( 'init', function() {
    if ( get_option( 'wp_glossary_pages_flush_needed' ) ) {
        flush_rewrite_rules();
        delete_option( 'wp_glossary_pages_flush_needed' );
    }
} );

// Register custom rewrite rules for letter pages (ex: /glossary/a/)
add_action('init', function() {
    $base_slug = (get_locale() === 'fr_FR') ? 'glossaire' : 'glossary';
    add_rewrite_rule(
        '^' . $base_slug . '/([a-zA-Z])/?$',
        'index.php?post_type=glossary&glossary_letter=$matches[1]',
        'top'
    );
});

add_filter('query_vars', function($vars) {
    $vars[] = 'glossary_letter';
    return $vars;
});


/***************************************************************
 * i18n
 ***************************************************************/


// Load plugin textdomain for translations
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'glossary-pages', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});


/***************************************************************
 * Include files
 ***************************************************************/


require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-glossary.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomy-glossary-category.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode-glossary.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/search-glossary.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/schema-glossary.php';


// Enqueue CSS
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'wp-glossary-css', plugins_url( 'assets/css/glossary.css', __FILE__ ) );
} );


/***************************************************************
 * Render the pages
 ***************************************************************/


// Define how the page should exist to display the letter page
add_action('template_redirect', function() {
    $letter = get_query_var('glossary_letter');
    if ($letter && get_query_var('post_type') === 'glossary') {
        // Force the list
        status_header(200);
        nocache_headers();
        // Call a specific function to render the page
        wp_glossary_pages_render_letter_page(strtoupper($letter));
        exit;
    }
});


// Display the content of the term for each letter page
function wp_glossary_pages_render_letter_page($letter) {
    get_header();
    echo '<div class="wp-glossary-page wrap">';
    /* translators: the string field is a letter for "A" to "Z" */
    echo '<h1>' . sprintf(esc_html__('Glossary: Terms starting with "%s"', 'glossary-pages'), esc_html($letter)) . '</h1>';

    // Term lists for this letter
    $args = [
        'post_type' => 'glossary',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'title_li' => '',
        'post_status' => 'publish',
        'suppress_filters' => false,
        'meta_query' => [],
    ];
    $args['title_like'] = $letter;

    // Custom get for each posts by title (because WP_Query does not filter natively on the first letter)
    $posts = get_posts($args);
    $filtered = [];
    foreach ($posts as $post) {
        if (strtoupper(substr($post->post_title, 0, 1)) === $letter) {
            $filtered[] = $post;
        }
    }

    if (count($filtered) > 0) {
        echo '<ul class="wp-glossary-list">';
        foreach ($filtered as $post) {
            $definition = get_post_meta($post->ID, '_wp_glossary_pages_definition', true);
            $link = get_permalink($post);
            echo '<li><a href="' . esc_url($link) . '">' . esc_html($post->post_title) . '</a>';
            if ($definition) {
                echo ': ' . esc_html($definition);
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>' . esc_html__('No terms found for this letter.', 'glossary-pages') . '</p>';
    }
    echo '</div>';
    get_footer();
}


// Display the custom field on the single page "glossary"
add_filter('the_content', function($content) {
    if ( is_singular('glossary') && in_the_loop() && is_main_query() ) {
        $synonym = get_post_meta(get_the_ID(), '_wp_glossary_pages_synonym', true);
        $example = get_post_meta(get_the_ID(), '_wp_glossary_pages_example', true);

        $output = '';
        if ($synonym || $example) {
            $output .= '<div class="wp-glossary-single-fields">';
            if ($synonym) {
                $output .= '<p><strong>' . esc_html__('Synonyms', 'glossary-pages') . ':</strong> ' . esc_html($synonym) . '</p>';
            }
            if ($example) {
                $output .= '<p><strong>' . esc_html__('Example', 'glossary-pages') . ':</strong> ' . esc_html($example) . '</p>';
            }
            $output .= '</div>';
        }
        return $content . $output;
    }
    return $content;
});


/***************************************************************
 * Help page
 ***************************************************************/


// Help page on the admin menu
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=glossary',                         // parent slug
        esc_html__('Glossary Pages Help', 'glossary-pages'),   // page title
        esc_html__('Help', 'glossary-pages'),                  // menu title
        'manage_options',                                      // capability
        'wp-glossary-help',                                    // menu slug
        'wp_glossary_pages_render_help_page'                   // callback
    );
});


// Content of the help page
function wp_glossary_pages_render_help_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('WP Glossary Pages - Help & Usage', 'glossary-pages'); ?></h1>
        <p><?php esc_html_e('WP Glossary Pages allows you to create and manage a glossary using custom post types.', 'glossary-pages'); ?></p>
        
        <h2><?php esc_html_e('How to use', 'glossary-pages'); ?></h2>
        <ul>
            <li><?php esc_html_e('Create glossary terms via the "Glossary" menu in the admin.', 'glossary-pages'); ?></li>
            <li><?php esc_html_e('Add definition, synonyms and example for each term.', 'glossary-pages'); ?></li>
            <li><?php esc_html_e('Group terms by category if desired.', 'glossary-pages'); ?></li>
        </ul>

        <h2><?php esc_html_e('Shortcodes', 'glossary-pages'); ?></h2>
        <dl>
            <dt><code>[wp_glossary_pages_nav]</code></dt>
            <dd>
                <?php esc_html_e('Displays only the alphabetical navigation bar (A-Z) for glossary terms. Use it anywhere to help users jump to terms starting with a specific letter.', 'glossary-pages'); ?>
                <br>
                <strong><?php esc_html_e('Available attributes:', 'glossary-pages'); ?></strong>
                <ul>
                    <li><code>show_all="1"</code> — <?php esc_html_e('Show all letters as links, even if there are no terms for some letters.', 'glossary-pages'); ?></li>
                </ul>
            </dd>

            <dt><code>[wp_glossary_pages_list]</code></dt>
            <dd>
                <?php esc_html_e('Displays the full list of glossary terms, grouped by starting letter.', 'glossary-pages'); ?>
                <br>
                <strong><?php esc_html_e('Available attributes:', 'glossary-pages'); ?></strong>
                <ul style="margin-top:0.2em;">
                    <li><code>category="slug"</code> — <?php esc_html_e('Filter glossary terms by category slug (optional). Example: [wp_glossary_pages_list category="seo"]', 'glossary-pages'); ?></li>
                </ul>
            </dd>

            <dt><code>[wp_glossary_pages_categories]</code></dt>
            <dd>
                <?php esc_html_e('Displays a menu with all glossary term categories. Each category links to its archive page.', 'glossary-pages'); ?>
                <br>
                <strong><?php esc_html_e('Available attributes:', 'glossary-pages'); ?></strong>
                <ul style="margin-top:0.2em;">
                    <li><code>show_count="1"</code> — <?php esc_html_e('Show the number of terms in each category.', 'glossary-pages'); ?></li>
                </ul>
            </dd>

            <dt><code>[wp_glossary_pages_search]</code></dt>
            <dd><?php esc_html_e('Displays a search form for glossary terms.', 'glossary-pages'); ?></dd>
        </dl>

        <p><?php esc_html_e('You can combine the navigation, categories menu, and list shortcodes for a classic glossary experience:', 'glossary-pages'); ?></p>
        <pre style="background: #f7f7f7; padding: 1em; border-radius: 8px;">[wp_glossary_pages_nav]
[wp_glossary_pages_list]</pre>

        <h2><?php esc_html_e('WPML/Polylang', 'glossary-pages'); ?></h2>
        <p><?php esc_html_e('The plugin is compatible with WPML and Polylang for multilingual glossaries.', 'glossary-pages'); ?></p>
        <?php /* ?>
        <hr>
        <p>
            <strong><?php esc_html_e('Support', 'glossary-pages'); ?>:</strong> 
            <a href="https://wordpress.org/support/plugin/" target="_blank">wordpress.org/support/plugin/</a>
        </p>
        <?php //*/ ?>
    </div>
    <?php
}
