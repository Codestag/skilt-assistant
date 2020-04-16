<?php
/**
 * Plugin Name: Skilt Assistant
 * Plugin URI: https://github.com/Codestag/skilt-assistant
 * Description: A plugin to assit Skilt theme with additional features.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0
 * Text Domain: skilt-assistant
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Skilt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Skilt_Assistant' ) ) :
	/**
	 * Skilt_Assistant Base Plugin Class.
	 *
	 * @since 1.0
	 */
	class Skilt_Assistant {

		/**
		 * Base instance property.
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Registers a plugin instance & loads required methods.
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Skilt_Assistant ) ) {
				self::$instance = new Skilt_Assistant();
				self::$instance->define_constants();
				self::$instance->init();
				self::$instance->includes();
			}
		}

		/**
		 * Registers constants.
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'SA_VERSION', '1.0' );
			$this->define( 'SA_DEBUG', true );
			$this->define( 'SA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'SA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Checks & defines undefined constants.
		 *
		 * @param string $name Contstant name.
		 * @param string $value Constant value.
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Initialize plugin hooks.
		 *
		 * @since 1.0
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'plugin_admin_assets' ) );
		}

		/**
		 * Loads required files.
		 *
		 * @since 1.0
		 */
		public function includes() {
			// Shortcodes.
			require_once SA_PLUGIN_PATH . 'includes/shortcodes/contact-form.php';

			if ( is_admin() ) : // Admin includes.
				require_once SA_PLUGIN_PATH . 'includes/meta/helpers-metabox.php';
				require_once SA_PLUGIN_PATH . 'includes/meta/background.php';
			endif;

		}

		/**
		 * Enqueue required scripts and styles.
		 *
		 * @param string $hook Current page slug.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function plugin_admin_assets( $hook ) {
			if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
				wp_enqueue_media();
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_style( 'stag-admin-metabox', SA_PLUGIN_URL . 'assets/css/stag-admin-metabox.css', array( 'wp-color-picker' ), SA_VERSION, 'screen' );
			}
		}
	}
endif;


/**
 * Registers plugin base class instance.
 *
 * @since 1.0
 */
function skilt_assistant() {
	return Skilt_Assistant::register();
}

/**
 * Plugin activation check notice.
 *
 * @since 1.0
 */
function skilt_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Skilt Assistant requires Skilt WordPress Theme to be installed and activated.', 'skilt-assistant' );
	echo '</p></div>';
}

/**
 * Plugin Activation Check.
 *
 * @since 1.0
 */
function skilt_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme.
	if ( 'Skilt' === $theme->name || 'Skilt' === $theme->parent_theme ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'after_setup_theme', 'skilt_assistant' );
		} else {
			skilt_assistant();
		}
	} else {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'skilt_assistant_activation_notice' );
	}
}

// Theme loads.
skilt_assistant_activation_check();
