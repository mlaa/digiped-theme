<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 *
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(
    function ($file) use ($sage_error) {
        $file = "./app/{$file}.php";
        if (!locate_template($file, true, true)) {
            $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
        }
    },
    [
        'helpers',
        'setup',
        'filters',
        'admin',
        // 'digiped-keyword',
        // 'digiped-artifact',
        'digiped-collection',
        'digiped-collections-rest-controller',
        'custom-post-type',
        'custom-taxonomy',
    ]
);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
Container::getInstance()
    ->bindIf(
        'config',
        function () {
            return new Config(
                [
                    'assets' => require __DIR__ . '/config/assets.php',
                    'theme' => require __DIR__ . '/config/theme.php',
                    'view' => require __DIR__ . '/config/view.php',
                ]
            );
        },
        true
    );

function cropWords($content, $count, $echo = true)
{
    $words = explode(" ", $content);
    $somewords = array_slice($words, 0, $count);
    $smallphrase = strip_tags(rtrim(preg_replace('/[A-Z]+(\.|\,|\.\,),/', '', implode(" ", $somewords)), ', and or . 1'));
    if (!$echo) {
        return $smallphrase;
    }
    echo $smallphrase;
    return true;
}

function change_search_url()
{
    if (is_search() && !empty($_GET['s'])) {
        wp_redirect(home_url("/search/") . urlencode(get_query_var('s')));
        exit();
    }
}
add_action('template_redirect', 'change_search_url');

function queryfilter($query)
{
    // for keyword archive page
    if (is_post_type_archive('digiped_keyword')) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set('posts_per_page', -1);
        return;
    }

    //for search
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('digiped_artifact', 'digiped_keyword', 'artifact', 'collection'));
        $query->set('orderby', array('post_title' => 'ASC'));
        $query->set('posts_per_page', '100');
        return $query;
    }

    return $query;
}

add_filter('pre_get_posts', 'queryfilter');

function createCustomPostType($post_type_name, $args)
{

    if (empty($args['labels'])) {
        $args['labels'] = array(
            'name' => _x($post_type_name, "", 'learningspace'),
            'singular_name' => _x($post_type_name, "", 'learningspace'),
        );
    }

    //required for gutenberg
    //$args['show_in_rest'] = true;

    // Registering your Custom Post Type
    register_post_type($post_type_name, $args);
}

function createCustomTaxonomy($tax_name, $post_types = array("post"), $is_hierarchical = true, $labels = false, $show_ui = true)
{

    if (!$labels) {
        $labels = array(
            'name' => __($tax_name, 'tax_' . $tax_name),
            'singular_name' => __($tax_name, 'tax_' . $tax_name),
            'search_items' => __('Search ' . $tax_name),
            'all_items' => __('All ' . $tax_name),
            'edit_item' => __('Edit ' . $tax_name),
            'update_item' => __('Update ' . $tax_name),
            'add_new_item' => __('Add New ' . $tax_name),
            'new_item_name' => __('New ' . ' Name'),
            'parent_item' => __('Parent Topic'),
            'parent_item_colon' => __('Parent Topic:'),
        );
    }

    register_taxonomy(strtolower(str_replace(" ", "_", $tax_name)), $post_types, array(
        'hierarchical' => $is_hierarchical,
        'labels' => $labels,
        'show_ui' => $show_ui,
        'show_in_menu' => true,
        'show_in_nav_menu' => false,
        'show_in_admin_bar' => true,
        'show_admin_column' => true,
        'public' => true,
        'has_archive' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => strtolower(str_replace("_", "-", $tax_name)),
            'with_front' => false,
        ),
    ));
}

add_action('init', 'custom_taxonomy_flush_rewrite');
function custom_taxonomy_flush_rewrite()
{
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
