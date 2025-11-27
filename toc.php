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

if(!defined('TOC_URL')){
    define('TOC_URL', plugin_dir_url( __FILE__ ));
}


