<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://edeneye.com
 * @since      1.0.0
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/admin
 * @author     Edeneye <wordpress@edeneye.com>
 */
class Breaking_Bar_Public {

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
	 * Variable to hold Breaking Bar posts
	 *
	 * @since    1.0.0
	 */
	private $breaking_posts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $name    The name of the plugin.
	 * @var      string $version The version of this plugin.
	 * @var      array  $options The options for this plugin.
	 */
	public function __construct( $name, $version, $options ) {

		$this->name    = $name;
		$this->version = $version;
		$this->options = $options;

	}

	/**
	 * Check if Breaking Bar has posts. If so, add the CSS to the head
	 *
	 * @since    1.0.0
	 */
	public function check_for_bb() {

		// Check for Breaking Bar posts
		$args                 = array( 'numberposts' => 1, 'category' => $this->options['category'] );
		$this->breaking_posts = wp_get_recent_posts( $args );

		// If there are Breaking Bar posts, continue
		if ( ! empty( $this->breaking_posts ) ) {

			// Add CSS styles
			$output = '<style>' .
				'#breaking-bar {background-color:' . $this->options['bg_color'] . ';margin:0 0 20px;}' .
				'#breaking-bar h3 {color:' . $this->options['text_color'] . ';margin:0;padding:0.5em;}' .
				'#breaking-bar a {color:' . $this->options['link_color'] . '}' .
				'</style>';

			echo $output;
		}

	}

	/**
	 * Function called by shortcode to display breaking bar
	 *
	 * @since    1.0.0
	 *
	 * @return string
	 */
	public function display_breaking_bar() {

		if ( ! empty( $this->breaking_posts ) ) {

			// Posts have already been retrieved in 'check_for_bb'. Display first one
			$breaking_post = current( $this->breaking_posts );

			$breaking_bar = '<div id="breaking-bar">';
			$breaking_bar .= '<h3>';
			if ( ! empty( $this->options['label'] ) ) {
				$breaking_bar .= $this->options['label'] . ': ';
			}
			$breaking_bar .= '<a href="' . get_permalink( $breaking_post['ID'] ) . '" title="' . esc_attr( $breaking_post['post_title'] ) . '" >' . $breaking_post["post_title"] . '</a>';
			$breaking_bar .= '</h3>';
			$breaking_bar .= '</div>';

			return $breaking_bar;

		} else {

			return false;

		}
	}

}
