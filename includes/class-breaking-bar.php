<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://edeneye.com
 * @since      1.0.0
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/includes
 * @author     Edeneye <wordpress@edeneye.com>
 */
class Breaking_Bar {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Breaking_Bar_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin options
	 *
	 * @since    1.0.0
	 */
	protected $options;

	/**
	 * Default options
	 *
	 * @since    1.0.0
	 */
	protected $default_options;

	/**
	 * Plugin options name
	 *
	 * @since    1.0.0
	 */
	protected $options_name;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name  = 'breaking-bar';
		$this->version      = '1.0.0';
		$this->options_name = 'breaking_bar_options';

		// Set the default options for the plugin
		$this->default_options = array(
			'version'    => $this->version,
			'bg_color'   => '#4A434F',
			'text_color' => '#FFFFFF',
			'link_color' => '#FFFFFF',
			'label'      => '',
			'category'   => null,
		);

		// Get registered options
		$this->set_options();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Function to retrieve options from database
	 * Also performs version check and integrity of options
	 *
	 * @since    1.0.0
	 */
	private function set_options() {

		// If options are set already, return
		if ( isset( $this->options ) ) {
			return $this->options;
		}

		// Get plugin options from database
		$db_options = get_option( $this->options_name );

		// Options exist
		if ( $db_options !== false ) {

			if ( ! empty( $db_options['version'] ) ) {
				$new_version = version_compare( $db_options['version'], $this->version, '!=' );
			} else {
				$new_version = true;
			}
			$desync = array_diff_key( $this->default_options, $db_options ) !== array_diff_key( $db_options, $this->default_options );

			// update options if version changed, or we have missing/extra (out of sync) option entries
			if ( $new_version || $desync ) {

				$new_options = array();

				// check for new options and set defaults if necessary
				foreach ( $this->default_options as $option => $value ) {
					$new_options[$option] = isset( $db_options[$option] ) && $db_options[$option] != '' ? $db_options[$option] : $value;
				}

				// update version info
				$new_options['version'] = $this->version;

				update_option( $this->options_name, $new_options );
				$this->options = $new_options;

			} // no update was required
			else {
				$this->options = $db_options;
			}

		} else {
			// new install (plugin was just activated)
			update_option( $this->options_name, $this->default_options );
			$this->options = $this->default_options;
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Breaking_Bar_Loader. Orchestrates the hooks of the plugin.
	 * - Breaking_Bar_i18n. Defines internationalization functionality.
	 * - Breaking_Bar_Admin. Defines all hooks for the dashboard.
	 * - Breaking_Bar_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-breaking-bar-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-breaking-bar-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-breaking-bar-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-breaking-bar-public.php';

		$this->loader = new Breaking_Bar_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Breaking_Bar_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Breaking_Bar_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Breaking_Bar_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_options(), $this->get_options_name() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Initialize plugin options
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_options' );

		// Add the options page and menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->get_plugin_name() . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Breaking_Bar_Public( $this->get_plugin_name(), $this->get_version(), $this->get_options() );

		// Check to see if Breaking Bar should be displayed
		$this->loader->add_action( 'wp_head', $plugin_public, 'check_for_bb' );

		// Add Breaking Bar shortcode
		add_shortcode( 'breaking-bar', array( $plugin_public, 'display_breaking_bar' ) );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Breaking_Bar_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the options for the plugin.
	 *
	 * @since     1.0.0
	 * @return    array    The options for the plugin.
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Retrieve the options name for the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The options name for the plugin.
	 */
	public function get_options_name() {
		return $this->options_name;
	}

}
