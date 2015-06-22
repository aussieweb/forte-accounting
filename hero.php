<?php

/**
 * hero.php
 * Template for hero.
 */

?>


<header class="bg-primary bg-hero text-center padding-top-large padding-bottom-large <?php if ( !is_page( 'services' ) ) { echo 'margin-bottom'; } ?>">

	<?php

		// Variables
		global $post;
		$keel_get_the_page_id = is_page() ? $post->ID : get_option( 'page_for_posts' ); // Get the page ID, or the home ID if posts page
		$keel_get_page_subtitle = get_post_meta( $post->ID, 'keel_page_subtitle', true );
		$keel_get_page_cta = get_post_meta( $post->ID, 'keel_page_calltoaction', true );
		$hero_subtitle_margin = empty( $keel_get_page_cta ) ? 'no-margin-bottom' : '';
		$hero_subtitle = empty( $keel_get_page_subtitle ) ? '' : '<p class="text-large ' . $hero_subtitle_margin . '">' . $keel_get_page_subtitle . '</p>';

		// If is blog
		if ( is_home() || is_single() || is_page( 'all-resources' ) ) {
			$resources_id = keel_get_page_id_from_path(  '/resources' );
			$keel_get_page_cta = get_post_meta( $resources_id, 'keel_page_calltoaction', true );
			$hero_subtitle_margin = empty( $keel_get_page_cta ) ? 'no-margin-bottom' : '';
			$resources_subtitle = get_post_meta( $resources_id, 'keel_page_subtitle', true );
			$hero_subtitle = empty( $resources_subtitle ) ? '' : '<p class="text-large ' . $hero_subtitle_margin . '">' . $resources_subtitle . '</p>';
		}

	?>

	<div class="container container-large">
		<h1 class="text-hero margin-bottom-small no-padding-top"><?php echo get_the_title( $keel_get_the_page_id ); ?></h1>
	</div>
	<div class="container">
		<?php
			echo stripslashes( $hero_subtitle );
			echo stripslashes( $keel_get_page_cta );
		?>
	</div>

</header>