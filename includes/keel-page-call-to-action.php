<?php

	/**
	 * Page call-to-action
	 */

	// Create a metabox
	function keel_add_page_calltoaction() {
		add_meta_box( 'keel_add_page_calltoaction_field', 'Add page calltoaction', 'keel_add_page_calltoaction_field', 'page', 'normal', 'high');
	}
	add_action('add_meta_boxes', 'keel_add_page_calltoaction');


	// Add checkbox to the metabox
	function keel_add_page_calltoaction_field() {

		global $post;

		// Get checkedbox value
		$page_calltoaction = stripslashes( get_post_meta( $post->ID, 'keel_page_calltoaction', true ) );
		$settings = array(
			'textarea_name' => 'keel_page_calltoaction',
			'textarea_rows' => 8,
			'media_buttons' => false,
		);

		// Insert editor
		wp_editor( $page_calltoaction, 'keel_page_calltoaction', $settings );

		?>
		<label class="description" for="landing_cta"><?php _e( 'Add a page call-to-action here.', 'keel' ); ?></label>
		<?php

		// Security field
		wp_nonce_field( 'keel-add-page-calltoaction-nonce', 'keel-add-page-calltoaction-process' );

	}

	// Save checkbox data
	function keel_save_page_calltoaction( $post_id, $post ) {

		// Verify data came from edit screen
		if ( !wp_verify_nonce( $_POST['keel-add-page-calltoaction-process'], 'keel-add-page-calltoaction-nonce' ) ) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if ( !current_user_can( 'edit_post', $post->ID )) {
			return $post->ID;
		}

		// Update data in database
		$calltoaction = $_POST['keel_page_calltoaction'];
		if ( isset( $calltoaction ) ) {
			update_post_meta( $post->ID, 'keel_page_calltoaction', wp_filter_post_kses( $calltoaction ) );
		} else {
			delete_post_meta( $post->ID, 'keel_page_calltoaction' );
		}

	}
	add_action('save_post', 'keel_save_page_calltoaction', 1, 2);