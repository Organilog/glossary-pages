=== Glossary Pages ===
Contributors: Organilog
Tags: glossary, lexicon, dictionary, definition, encyclopedia
Requires at least: 5.8
Tested up to: 6.8
Stable tag: 1.2.0
Requires PHP: 7.2
Donate link: https://fr.organilog.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A customizable, multilingual-ready glossary plugin with A-Z navigation, category filters, and search. Lightweight, flexible, and SEO-friendly.

== Description ==

**Glossary Pages** is the easiest way to add a flexible, multilingual glossary (lexicon) to your WordPress site. 
Organize your terms with custom post types, alphabetic navigation, categories, search, and fully compatible with WPML or Polylang.

On the backend of your WordPress account, access 

**Features:**
- Custom Post Type "Glossary" (slug: /glossary/ or /glossaire/ auto for French)
- Add synonyms and example fields for each term
- A-Z navigation bar (shortcode)
- Category filter (shortcode)
- Dedicated page per letter (e.g. /glossary/a/)
- Search form for glossary terms
- SEO-friendly (Schema.org markup for terms)
- Ready for WPML and Polylang
- Responsive and accessible
- Easy to adapt to another language : .pot file is included
- French and English translation files included

**Use cases**

- **Dictionary or encyclopedia**: Create detailed entries for terms, concepts, or historical facts.
- **Tooltip glossary**: Display definitions as tooltips when users hover over terms in your content.
- **Knowledge base**: Organize internal or public documentation with clear explanations of key terms.
- **SEO optimization**: Improve internal linking and increase search engine visibility by creating individual pages for each term.
- **Synonyms and lexicon**: Group related words or industry-specific vocabulary for quick reference.
- **Vocabulary builder**: Help learners acquire new words with definitions, examples, and related terms.
- **Company knowledge base**: Share internal terminology with employees or collaborators.
- **Customer support**: Define technical jargon or product-specific terms for your users.
- **Educational websites**: Provide definitions and explanations for course-related terms.
- **Medical or legal glossaries**: Centralize complex vocabulary for easier reference.

== Installation ==

1. Upload the `wp-glossary` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. (First install: visit “Settings > Permalinks” and click “Save Changes” once)
4. Start adding terms under the new “Glossary” menu
5. Add [shortcodes](#shortcodes) where you want to display your glossary

== Frequently Asked Questions ==

= How do I display the glossary navigation bar? =
Use the shortcode `[wp_glossary_pages_nav]` anywhere in your content.

= Can I display only letters that have terms? =
Yes! By default, `[wp_glossary_pages_nav]` only links letters that have at least one term. Use `[wp_glossary_pages_nav show_all="1"]` to link all letters.

= How do I display the list of terms? =
Use the shortcode `[wp_glossary_pages_list]`. You can filter by category: `[wp_glossary_pages_list category="category_name"]` (replace "category_name" by any category of your choice).

= How do I show the categories menu? =
Use the shortcode `[wp_glossary_pages_categories]`. You can display the count of terms in each category: `[wp_glossary_pages_categories show_count="1"]`.

= How do I enable search? =
Use `[wp_glossary_pages_search]` anywhere to display a search field for glossary terms.

= Does it support Gutenberg? =
Not yet. Use shortcodes in your posts or pages.

= How does SEO work? =
Each glossary term uses schema.org markup for best search engine compatibility, and each letter/category has its own indexable URL.

= Is it compatible with WPML/Polylang? =
Yes, all terms, categories and fields can be translated. The main CPT and taxonomies are registered as translatable.

== Screenshots ==

1. Example of the glossary A-Z navigation and list
2. Edit screen for a glossary term (with synonyms and example)
3. Glossary category menu

== Shortcodes ==

- `[wp_glossary_pages_nav show_all="0|1"]`  
  Displays the A-Z navigation bar.  
  - `show_all="1"`: show all letters as links, even if no terms for some letters.  
  - `show_all="0"`: only link letters with terms (default).

- `[wp_glossary_pages_list category="slug"]`  
  Displays the list of terms, grouped by letter.  
  - `category`: filter by category slug (optional).

- `[wp_glossary_pages_categories show_count="1"]`  
  Displays the categories menu.  
  - `show_count="1"`: show the number of terms in each category.

- `[wp_glossary_pages_search]`  
  Displays a search field for glossary terms.

== Changelog ==

= 1.2.0 =
* Improved navigation shortcode: can hide links for empty letters
* Help page moved as sub-menu of CPT
* Improved translation and admin help page

= 1.1.0 =
* Dedicated page per letter (e.g. /glossary/a/)
* [wp_glossary_pages_categories] shortcode for category menu
* Shortcodes split ([wp_glossary_pages_nav], [wp_glossary_pages_list])
* Improved activation rewrite logic

= 1.0.0 =
* First release: custom post type, synonyms & example fields, navigation, category, search, SEO, multilingual.

== Upgrade Notice ==

= 1.2.0 =
Navigation can now hide links for empty letters. Admin help improved.

== License ==

GPL v2 or later

== How to uninstall WP Sitemap Page ==
To uninstall WP Sitemap Page, you just have to de-activate the plugin from the plugins list.
