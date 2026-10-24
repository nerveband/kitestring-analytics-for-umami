<?php
namespace KitestringUmami;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for managing the options.
 *
 * @since 0.1.0
 */
class Options {
	/**
	 * Get the options.
	 *
	 * @since 0.1.0
	 * @change 0.2.0 - Added default for ignore_admin.
	 * @change 0.6.0 - Added default for track_comments.
	 * @change 0.8.0 - Add migration for old options.
	 * @change 1.0.0 - Add migration from integrate_umami_options.
	 *
	 * @return array
	 */
	public static function get_options(): array {
		self::maybe_migrate_options();
		return wp_parse_args(
			get_option( 'kitestring_umami_options' ),
			array(
				'enabled'           => 0,
				'script_url'        => '',
				'host_url'          => '',
				'website_id'        => '',
				'use_host_url'      => 0,
				'ignore_admins'     => 1,
				'auto_track'        => 1,
				'do_not_track'      => 1,
				'track_comments'    => 0,
				'tag'               => '',
				'domains'           => '',
				'exclude_search'    => 0,
				'exclude_hash'      => 0,
				'before_send'       => '',
				'auto_pageview'     => 1,
				'performance'       => 0,
				'distinct_id'       => '',
				'fetch_credentials' => 'omit',
				'recorder_enabled'  => 0,
				'recorder_url'      => '',
				'api_key'           => '',
				'api_username'      => '',
				'api_password'      => '',
			)
		);
	}

	/**
	 * Resolve the tracker directory without losing a custom port or base path.
	 *
	 * @param string $url Tracker script URL.
	 * @return string Collection base URL.
	 */
	public static function get_script_base_url( string $url ): string {
		$parts = wp_parse_url( $url );
		if ( ! is_array( $parts ) || empty( $parts['host'] ) ) {
			return '';
		}
		$base = ( $parts['scheme'] ?? 'https' ) . '://' . $parts['host'];
		if ( isset( $parts['port'] ) ) {
			$base .= ':' . $parts['port'];
		}
		$path  = $parts['path'] ?? '';
		$slash = strrpos( $path, '/' );
		if ( false !== $slash ) {
			$base .= substr( $path, 0, $slash );
		}
		return $base;
	}


	/**
	 *  Migrate options from old version.
	 *
	 * @since 0.8.0 - Migrate options from old version.
	 * @since 1.0.0 - Migrate from integrate_umami_options and umami_options.
	 */
	private static function maybe_migrate_options() {
		if ( false !== get_option( 'kitestring_umami_options', false ) ) {
			return;
		}
		foreach ( array( 'simply_umami_options', 'integrate_umami_options', 'umami_options' ) as $legacy_key ) {
			$legacy_options = get_option( $legacy_key, false );
			if ( is_array( $legacy_options ) ) {
				update_option( 'kitestring_umami_options', $legacy_options );
				return;
			}
		}
	}
}
