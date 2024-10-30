<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://edeneye.com
 * @since      1.0.0
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/admin
 * @author     Edeneye <wordpress@edeneye.com>
 */
class Breaking_Bar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $name The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The options for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The current saved options for this plugin.
	 */
	private $options;

	/**
	 * The options name for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options name for this plugin.
	 */
	private $options_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $name         The name of this plugin.
	 * @var      string $version      The version of this plugin.
	 * @var      array  $options      The options for this plugin.
	 * @var      string $options_name The options name for this plugin.
	 */
	public function __construct( $name, $version, $options, $options_name ) {

		$this->name         = $name;
		$this->version      = $version;
		$this->options      = $options;
		$this->options_name = $options_name;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Breaking_Bar_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Breaking_Bar_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Add the color picker css file
		wp_enqueue_style( 'wp-color-picker' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Breaking_Bar_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Breaking_Bar_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/breaking-bar-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}

	/**
	 * Register and add settings
	 */
	public function register_options() {

		register_setting(
			'breaking_bar_settings', // Option group
			$this->options_name, // Option name
			array( $this, 'validate_options' ) // Validate options
		);

		add_settings_section(
			'breaking_bar_main', // ID
			'', // Title
			array( $this, 'print_section_info' ), // Callback
			$this->name // Page
		);

		add_settings_field(
			'category', // ID
			'Category', // Title
			array( $this, 'category_callback' ), // Callback
			$this->name, // Page
			'breaking_bar_main' // Section
		);

		add_settings_field(
			'label', // ID
			'Text Label', // Title
			array( $this, 'label_callback' ), // Callback
			$this->name, // Page
			'breaking_bar_main' // Section
		);

		add_settings_field(
			'bg_color', // ID
			'Background Color', // Title
			array( $this, 'bg_color_callback' ), // Callback
			$this->name, // Page
			'breaking_bar_main' // Section
		);

		add_settings_field(
			'text_color', // ID
			'Text Color', // Title
			array( $this, 'text_color_callback' ), // Callback
			$this->name, // Page
			'breaking_bar_main' // Section
		);

		add_settings_field(
			'link_color', // ID
			'Link Color', // Title
			array( $this, 'link_color_callback' ), // Callback
			$this->name, // Page
			'breaking_bar_main' // Section
		);

	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print '';
	}

	/**
	 * Function that will validate all fields.
	 */
	public function validate_options( $fields ) {

		$valid_fields = array();

		// Validate Background Color
		if ( isset( $fields['bg_color'] ) ) {
			$background = sanitize_text_field( $fields['bg_color'] );

			// Check if is a valid hex color
			if ( false === $this->check_color( $background ) ) {

				// Set the error message
				add_settings_error( $this->options_name, 'bb_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type

				// Get the previous valid value
				$valid_fields['bg_color'] = $this->options['bg_color'];

			} else {

				$valid_fields['bg_color'] = $background;

			}
		}

		// Validate Text Color
		if ( isset( $fields['text_color'] ) ) {
			$text_color = sanitize_text_field( $fields['text_color'] );

			// Check if is a valid hex color
			if ( false === $this->check_color( $text_color ) ) {

				// Set the error message
				add_settings_error( $this->options_name, 'bb_bg_error', 'Insert a valid color for text', 'error' );

				// Get the previous valid value
				$valid_fields['text_color'] = $this->options['text_color'];

			} else {

				$valid_fields['text_color'] = $text_color;

			}
		}

		// Sanitize text label
		if ( isset ( $fields['label'] ) ) {
			$label                 = sanitize_text_field( $fields['label'] );
			$valid_fields['label'] = $label;
		}

		// Make sure category is an integer
		if ( isset ( $fields['category'] ) ) {
			$category                 = intval( $fields['category'] );
			$category                 = absint( $category );
			$valid_fields['category'] = $category;

		}

		return $valid_fields;
	}

	/**
	 * Function that will check if value is a valid HEX color.
	 */
	public function check_color( $value ) {

		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}

		return false;
	}

	/**
	 * Callback to display background color option field
	 */
	public function bg_color_callback() {
		$bg_color = ( isset( $this->options['bg_color'] ) ) ? $this->options['bg_color'] : '';
		echo '<input type="text" name="' . $this->options_name . '[bg_color]" value="' . $bg_color . '" class="bb-color-picker" />';

	}

	/**
	 * Callback to display text color option field
	 */
	public function text_color_callback() {
		$text_color = ( isset( $this->options['text_color'] ) ) ? $this->options['text_color'] : '';
		echo '<input type="text" name="' . $this->options_name . '[text_color]" value="' . $text_color . '" class="bb-color-picker" />';

	}

	/**
	 * Callback to display link color option field
	 */
	public function link_color_callback() {
		$link_color = ( isset( $this->options['link_color'] ) ) ? $this->options['link_color'] : '';
		echo '<input type="text" name="' . $this->options_name . '[link_color]" value="' . $link_color . '" class="bb-color-picker" />';

	}

	/**
	 * Callback to display label option field
	 */
	public function label_callback() {
		$label = ( isset( $this->options['label'] ) ) ? $this->options['label'] : '';
		echo '<input type="text" name="' . $this->options_name . '[label]" value="' . $label . '" />';
		echo '<p class="description">Enter a text label for the breaking bar, (e.g. "Breaking, Latest, etc.")</p>';
	}

	/**
	 * Callback to display category option field
	 */
	public function category_callback() {
		$category = ( isset( $this->options['category'] ) ) ? $this->options['category'] : '';
		$args     = array(
			'orderby'    => 'name',
			'selected'   => $category,
			'name'       => $this->options_name . '[category]',
			'hide_empty' => 0,
			'echo'       => 0
		);
		$output   = wp_dropdown_categories( $args );
		$output .= '<p class="description">Choose the category for the breaking bar to monitor</p>';
		echo $output;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 */
		add_options_page(
			__( 'Breaking Bar Settings', $this->name ),
			__( 'Breaking Bar', $this->name ),
			'manage_options',
			$this->name,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'partials/breaking-bar-admin-display.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->name ) . '">' . __( 'Settings', $this->name ) . '</a>'
			),
			$links
		);

	}

}
