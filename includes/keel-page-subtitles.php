<?php

	/**
	 * Page subtitle
	 */

	// Create a metabox
	function keel_add_page_subtitle() {
		add_meta_box( 'keel_add_page_subtitle_field', 'Add page subtitle', 'keel_add_page_subtitle_field', 'page', 'normal', 'high');
	}
	add_action('add_meta_boxes', 'keel_add_page_subtitle');


	// Add checkbox to the metabox
	function keel_add_page_subtitle_field() {

		global $post;

		// Get checkedbox value
		$page_subtitle = stripslashes( esc_attr(  get_post_meta( $post->ID, 'keel_page_subtitle', true ) ) );

		?>

			<div id="subtitlediv">
				<div id="subtitlewrap">
					<label class="" id="keel-subtitle-prompt-text" for="keel-post-subtitle">Enter subtitle here</label>
					<input type="text" class="large-text" name="keel_page_subtitle" size="30" value="<?php echo $page_subtitle; ?>" id="keel-post-subtitle" spellcheck="true" autocomplete="off">
				</div>
			</div>

		<?php

		// Security field
		wp_nonce_field( 'keel-add-page-subtitle-nonce', 'keel-add-page-subtitle-process' );

	}

	// Save checkbox data
	function keel_save_page_subtitle( $post_id, $post ) {

		// Verify data came from edit screen
		if ( !wp_verify_nonce( $_POST['keel-add-page-subtitle-process'], 'keel-add-page-subtitle-nonce' ) ) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if ( !current_user_can( 'edit_post', $post->ID )) {
			return $post->ID;
		}

		// Update data in database
		$subtitle = $_POST['keel_page_subtitle'];
		if ( isset( $subtitle ) ) {
			update_post_meta( $post->ID, 'keel_page_subtitle', wp_filter_post_kses( $subtitle ) );
		} else {
			delete_post_meta( $post->ID, 'keel_page_subtitle' );
		}

	}
	add_action('save_post', 'keel_save_page_subtitle', 1, 2);