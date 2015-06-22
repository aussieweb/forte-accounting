<?php

	/**
	 * Theme options
	 */


	/**
	 * Register the form setting for our keel_options array.
	 *
	 * This function is attached to the admin_init action hook.
	 *
	 * This call to register_setting() registers a validation callback, keel_theme_options_validate(),
	 * which is used when the option is saved, to ensure that our option values are properly
	 * formatted, and safe.
	 */
	function keel_theme_options_init() {
		register_setting(
			'keel_options', // Options group, see settings_fields() call in keel_theme_options_render_page()
			'keel_theme_options', // Database option, see keel_get_theme_options()
			'keel_theme_options_validate' // The sanitization callback, see keel_theme_options_validate()
		);

		// Register our settings field group
		add_settings_section(
			'general', // Unique identifier for the settings section
			'', // Section title (we don't want one)
			'__return_false', // Section callback (we don't want anything)
			'theme_options' // Menu slug, used to uniquely identify the page; see keel_theme_options_add_page()
		);

		// Register our individual settings fields
		add_settings_field( 'sidebar_main', __( 'Footer Content', 'keel' ), 'keel_settings_field_sidebar_main', 'theme_options', 'general' );
		// add_settings_field( 'sidebar_contact', __( 'Sidebar Contact', 'keel' ), 'keel_settings_field_sidebar_contact', 'theme_options', 'general' );
		// add_settings_field( 'sidebar_landing_cta', __( 'Sidebar Landing Page CTA', 'keel' ), 'keel_settings_field_sidebar_landing_cta', 'theme_options', 'general' );
		add_settings_field( 'sidebar_promotion', __( 'Sidebar Promotion', 'keel' ), 'keel_settings_field_sidebar_promotion', 'theme_options', 'general' );
		add_settings_field( 'resources', __( 'Resources', 'keel' ), 'keel_settings_field_resources', 'theme_options', 'general' );
		add_settings_field( 'resources_link', __( 'Resources Link Text', 'keel' ), 'keel_settings_field_resources_link', 'theme_options', 'general' );
		add_settings_field( 'updates_header', __( 'Updates Header', 'keel' ), 'keel_settings_field_updates_header', 'theme_options', 'general' );
		add_settings_field( 'updates_text', __( 'Updates Text', 'keel' ), 'keel_settings_field_updates_text', 'theme_options', 'general' );
		add_settings_field( 'updates_submit', __( 'Updates Submit Button Text', 'keel' ), 'keel_settings_field_updates_submit', 'theme_options', 'general' );
		add_settings_field( 'updates_rss', __( 'RSS Text', 'keel' ), 'keel_settings_field_updates_rss', 'theme_options', 'general' );
		add_settings_field( 'updates_url', __( 'Updates URL', 'keel' ), 'keel_settings_field_updates_url', 'theme_options', 'general' );
		add_settings_field( 'updates_spam', __( 'Updates Spam Protection', 'keel' ), 'keel_settings_field_updates_spam', 'theme_options', 'general' );
		add_settings_field( 'feedburner_url', __( 'Feedburner URL', 'keel' ), 'keel_settings_field_feedburner_url', 'theme_options', 'general' );

	}
	add_action( 'admin_init', 'keel_theme_options_init' );

	/**
	 * Change the capability required to save the 'keel_options' options group.
	 *
	 * @see keel_theme_options_init() First parameter to register_setting() is the name of the options group.
	 * @see keel_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
	 *
	 * @param string $capability The capability used for the page, which is manage_options by default.
	 * @return string The capability to actually use.
	 */
	function keel_option_page_capability( $capability ) {
		return 'edit_theme_options';
	}
	add_filter( 'option_page_capability_keel_options', 'keel_option_page_capability' );

	/**
	 * Add our theme options page to the admin menu.
	 * This function is attached to the admin_menu action hook.
	 */
	function keel_theme_options_add_page() {
		$theme_page = add_theme_page(
			__( 'Theme Options', 'keel' ),   // Name of page
			__( 'Theme Options', 'keel' ),   // Label in menu
			'edit_theme_options',          // Capability required
			'theme_options',               // Menu slug, used to uniquely identify the page
			'keel_theme_options_render_page' // Function that renders the options page
		);
	}
	add_action( 'admin_menu', 'keel_theme_options_add_page' );

	/**
	 * Returns the options array for _s.
	 *
	 * @since _s 1.0
	 */
	function keel_get_theme_options() {
		$saved = (array) get_option( 'keel_theme_options' );
		$defaults = array(
			'sidebar_main' => '',
			'sidebar_contact' => '',
			'landing_cta' => '',
			'sidebar_promotion' => '',
			'resources' => '',
			'resources_link' => 'View All Resources &rarr;',
			'updates_header' => '',
			'updates_text' => '',
			'updates_submit' => '',
			'updates_rss' => '',
			'updates_url' => '',
			'updates_spam' => '',
			'feedburner_url' => '',
		);

		$defaults = apply_filters( 'keel_default_theme_options', $defaults );

		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );

		return $options;
	}

	/**
	 * Renders the sidebar main setting field.
	 */
	function keel_settings_field_sidebar_main() {
		$options = keel_get_theme_options();
		$content = stripslashes( $options['sidebar_main'] );
		$settings = array(
			'textarea_name' => 'keel_theme_options[sidebar_main]',
			'textarea_rows' => 8
		);
		?>
		<?php wp_editor( $content, 'sidebar_main', $settings ); ?>
		<label class="description" for="sidebar_main"><?php _e( 'Add content for the foooter area.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the sidebar main setting field.
	 */
	function keel_settings_field_sidebar_contact() {
		$options = keel_get_theme_options();
		?>
		<textarea class="large-text" type="text" name="keel_theme_options[sidebar_contact]" id="sidebar-contact" cols="50" rows="10" /><?php echo esc_textarea( stripslashes( $options['sidebar_contact'] ) ); ?></textarea>
		<label class="description" for="sidebar-contact"><?php _e( 'Add content for the sidebar contact area.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the sidebar landing page call to action field.
	 */
	function keel_settings_field_sidebar_landing_cta() {
		$options = keel_get_theme_options();
		$content = stripslashes( $options['landing_cta'] );
		$settings = array(
			'textarea_name' => 'keel_theme_options[landing_cta]',
			'textarea_rows' => 8
		);
		?>
		<?php wp_editor( $content, 'landing_cta', $settings ); ?>
		<label class="description" for="landing_cta"><?php _e( 'Add your landing page call-to-action here.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the sidebar promotion field.
	 */
	function keel_settings_field_sidebar_promotion() {
		$options = keel_get_theme_options();
		$content = stripslashes( $options['sidebar_promotion'] );
		$settings = array(
			'textarea_name' => 'keel_theme_options[sidebar_promotion]',
			'textarea_rows' => 8
		);
		?>
		<?php wp_editor( $content, 'sidebar_promotion', $settings ); ?>
		<label class="description" for="sidebar_promotion"><?php _e( 'Add content for the sidebar promotion area.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the resources field.
	 */
	function keel_settings_field_resources() {
		$options = keel_get_theme_options();
		$content = stripslashes( $options['resources'] );
		$settings = array(
			'textarea_name' => 'keel_theme_options[resources]',
			'textarea_rows' => 8
		);
		?>
		<?php wp_editor( $content, 'resources', $settings ); ?>
		<label class="description" for="resources"><?php _e( 'Add content for the blog resources area.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the resources page slug field.
	 */
	function keel_settings_field_resources_link() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="text" name="keel_theme_options[resources_link]" id="resources-page" value="<?php echo esc_attr( stripslashes( $options['resources_link'] ) ); ?>" />
		<label class="description" for="resources-page"><?php _e( 'The text for the "View All Resources" link.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates header field.
	 */
	function keel_settings_field_updates_header() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="text" name="keel_theme_options[updates_header]" id="updates-header" value="<?php echo esc_attr( stripslashes( $options['updates_header'] ) ); ?>" />
		<label class="description" for="updates-header"><?php _e( 'The header for your free updates section.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates text field.
	 */
	function keel_settings_field_updates_text() {
		$options = keel_get_theme_options();
		?>
		<textarea class="large-text" type="text" name="keel_theme_options[updates_text]" id="updates-text" cols="50" rows="5"><?php echo esc_attr( stripslashes( $options['updates_text'] ) ); ?></textarea>
		<label class="description" for="updates-text"><?php _e( 'The text for your free updates section.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates submit button field.
	 */
	function keel_settings_field_updates_submit() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="text" name="keel_theme_options[updates_submit]" id="updates-submit" value="<?php echo esc_attr( stripslashes( $options['updates_submit'] ) ); ?>" />
		<label class="description" for="updates-submit"><?php _e( 'The text for your free updates submit button.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates RSS text field.
	 */
	function keel_settings_field_updates_rss() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="text" name="keel_theme_options[updates_rss]" id="updates-rss" value="<?php echo esc_attr( stripslashes( $options['updates_rss'] ) ); ?>" />
		<label class="description" for="updates-rss"><?php _e( 'The text for your free updates RSS link.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates URL field.
	 */
	function keel_settings_field_updates_url() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="url" name="keel_theme_options[updates_url]" id="updates-url" value="<?php echo esc_url( stripslashes( $options['updates_url'] ) ); ?>" />
		<label class="description" for="updates-url"><?php _e( 'The URL for your free updates form.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the updates spam URL field.
	 */
	function keel_settings_field_updates_spam() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="text" name="keel_theme_options[updates_spam]" id="updates-spam" value="<?php echo esc_attr( stripslashes( $options['updates_spam'] ) ); ?>" />
		<label class="description" for="updates-spam"><?php _e( 'The spam protection name for your free updates form.', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the feedburner URL field.
	 */
	function keel_settings_field_feedburner_url() {
		$options = keel_get_theme_options();
		?>
		<input class="large-text" type="url" name="keel_theme_options[feedburner_url]" id="feedburner-url" value="<?php echo esc_url( stripslashes( $options['feedburner_url'] ) ); ?>" />
		<label class="description" for="feedburner-url"><?php _e( 'The URL for your Feedburner feed (Chris will explain this to you).', 'keel' ); ?></label>
		<?php
	}

	/**
	 * Renders the Theme Options administration screen.
	 *
	 * @since _s 1.0
	 */
	function keel_theme_options_render_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
			<h2><?php printf( __( '%s Theme Options', 'keel' ), $theme_name ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'keel_options' );
					do_settings_sections( 'theme_options' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Sanitize and validate form input. Accepts an array, return a sanitized array.
	 *
	 * @see keel_theme_options_init()
	 * @todo set up Reset Options action
	 *
	 * @param array $input Unknown values.
	 * @return array Sanitized theme options ready to be stored in the database.
	 */
	function keel_theme_options_validate( $input ) {
		$output = array();

		if ( isset( $input['sidebar_main'] ) && ! empty( $input['sidebar_main'] ) )
			$output['sidebar_main'] = wp_filter_post_kses( $input['sidebar_main'] );

		if ( isset( $input['sidebar_contact'] ) && ! empty( $input['sidebar_contact'] ) )
			$output['sidebar_contact'] = wp_filter_post_kses( $input['sidebar_contact'] );

		if ( isset( $input['landing_cta'] ) && ! empty( $input['landing_cta'] ) )
			$output['landing_cta'] = wp_filter_post_kses( $input['landing_cta'] );

		if ( isset( $input['sidebar_promotion'] ) && ! empty( $input['sidebar_promotion'] ) )
			$output['sidebar_promotion'] = wp_filter_post_kses( $input['sidebar_promotion'] );

		if ( isset( $input['resources'] ) && ! empty( $input['resources'] ) )
			$output['resources'] = wp_filter_post_kses( $input['resources'] );

		if ( isset( $input['resources_link'] ) && ! empty( $input['resources_link'] ) )
			$output['resources_link'] = wp_filter_post_kses( $input['resources_link'] );

		if ( isset( $input['updates_header'] ) && ! empty( $input['updates_header'] ) )
			$output['updates_header'] = wp_filter_post_kses( $input['updates_header'] );

		if ( isset( $input['updates_text'] ) && ! empty( $input['updates_text'] ) )
			$output['updates_text'] = wp_filter_post_kses( $input['updates_text'] );

		if ( isset( $input['updates_submit'] ) && ! empty( $input['updates_submit'] ) )
			$output['updates_submit'] = wp_filter_nohtml_kses( $input['updates_submit'] );

		if ( isset( $input['updates_rss'] ) && ! empty( $input['updates_rss'] ) )
			$output['updates_rss'] = wp_filter_post_kses( $input['updates_rss'] );

		if ( isset( $input['updates_url'] ) && ! empty( $input['updates_url'] ) )
			$output['updates_url'] = wp_filter_nohtml_kses( $input['updates_url'] );

		if ( isset( $input['updates_spam'] ) && ! empty( $input['updates_spam'] ) )
			$output['updates_spam'] = wp_filter_nohtml_kses( $input['updates_spam'] );

		if ( isset( $input['feedburner_url'] ) && ! empty( $input['feedburner_url'] ) )
			$output['feedburner_url'] = wp_filter_nohtml_kses( $input['feedburner_url'] );

		return apply_filters( 'keel_theme_options_validate', $output, $input );
	}