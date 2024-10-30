<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://edeneye.com
 * @since      1.0.0
 *
 * @package    Breaking_Bar
 * @subpackage Breaking_Bar/admin/partials
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php
		// This prints out all hidden setting fields
		settings_fields( 'breaking_bar_settings' );
		do_settings_sections( $this->name );
		submit_button();
		?>
	</form>

</div>
