<?php

/**
 * 
 * 
 * Plugin Name: TOC - Table Of Content
 * Description: A lightweight plugin that automatically generates a clean, customizable table of contents from your page or document headings, improving navigation and readability.
 * Version: 1.0
 * Author: Bhaskar Bhakt
 * 
 */

if (! defined('ABSPATH')) exit();

if (!defined('TOC_PATH')) {
    define('TOC_PATH', plugin_dir_path(__FILE__));
}

if (!defined('TOC_URL')) {
    define('TOC_URL', plugin_dir_url(__FILE__));
}
if (!defined('PLUGIN_NAME')) {
    define('PLUGIN_NAME', plugin_basename(__FILE__));
}

require_once TOC_PATH . 'includes/class-toc.php';
require_once TOC_PATH . 'includes/class-toc-settings.php';


function initPlugin()
{

    new TOC();
    new TOCSetting();
}

add_action('plugins_loaded', 'initPlugin');


add_filter('plugin_action_links_' . PLUGIN_NAME, 'my_plugin_settings');

function my_plugin_settings($settings)
{
    $url = esc_url(add_query_arg(
        'page',
        'toc-setting',
        get_admin_url() . 'admin.php'
    ));
    $settings[] = '<a href="' . $url . '">Settings</a>';
    return $settings;
}
