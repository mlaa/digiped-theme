<?php

namespace App;

use Roots\Sage\Container;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    if (remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
        wp_enqueue_scripts();
    }

    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views'
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                    ];
                })
                ->concat([
                    "{$template}.blade.php",
                    "{$template}.php",
                ]);
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return true;
    return $display;
}


function create_post_type($post_type_name, $args)
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

function create_taxonomy($tax_name, $post_types = array("post"), $is_hierarchical = true, $labels = false, $show_ui = true)
{
    if (!$labels) {
        $labels = array(
            'name' => _x($tax_name, $tax_name),
            'singular_name' => _x($tax_name, $tax_name),
            'search_items' => __('Search ' . $tax_name),
            'all_items' => __('All ' . $tax_name),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit ' . $tax_name),
            'update_item' => __('Update ' . $tax_name),
            'add_new_item' => __('Add New ' . $tax_name),
            'new_item_name' => __('New ' . $tax_name . ' Name'),
            'menu_name' => __($tax_name),
        );
    }
    if ($is_hierarchical && empty($labels['parent_item'])) {
        $labels['parent_item'] = __('Parent Topic');
        $labels['parent_item_colon'] = __('Parent Topic:');
    }
    register_taxonomy(strtolower(str_replace(" ", "_", $tax_name)), $post_types, array(
        'hierarchical' => $is_hierarchical,
        'labels' => $labels,
        'show_ui' => $show_ui,
        'show_in_menu' => false,
        'show_in_nav_menu' => false,
        'show_in_admin_bar' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => $tax_name),
    ));
}
