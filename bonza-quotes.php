<?php
/**
 * Plugin Name: Bonza Quotes
 * Description: A simple quote management plugin with frontend form and admin UI.
 * Version: 1.0.0
 * Author: Ronnel
 * Text Domain: bonza-quotes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'BONZA_QUOTES_FILE' ) ) {
    define( 'BONZA_QUOTES_FILE', __FILE__ );
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    wp_die( 'Autoloader not found. Please run composer install in the plugin directory.' );
}
require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( \BonzaQuotes\Plugin::class ) && file_exists( __DIR__ . '/src/Plugin.php' ) ) {
    require_once __DIR__ . '/src/Plugin.php';
}

use BonzaQuotes\Plugin;

Plugin::run();