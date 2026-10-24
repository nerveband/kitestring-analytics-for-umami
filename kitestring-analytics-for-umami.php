<?php
/**
 * Plugin Name: Kitestring Analytics for Umami
 * Description: Independent WordPress integration for Umami Analytics. Not affiliated with or endorsed by Umami.
 * Version: 1.1.4
 * Author: Ashraf Ali
 * Author URI: https://ashrafali.net
 * Plugin URI: https://github.com/nerveband/simply-umami
 * License: GPLv3 or later
 * Text Domain: kitestring-analytics-for-umami
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * Based on Integrate Umami by Ancocodet (https://github.com/Ancocodet/wp-umami).
 *
 * @package Kitestring Analytics for Umami
 */

namespace KitestringUmami;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

define( 'KITESTRING_UMAMI_VERSION', '1.1.4' );
define( 'KITESTRING_UMAMI_BASE_FILE', __FILE__ );

/**
 * Init plugin.
 *
 * @since 0.1.0
 */
function init() {
	new Manager();
	new Settings();
}

\add_action( 'plugins_loaded', 'KitestringUmami\init' );
