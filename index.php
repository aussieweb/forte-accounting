<?php

/**
 * index.php
 * Template for the page that displays all of your posts.
 */

get_header(); ?>


<?php if (have_posts()) : ?>

	<?php
		// Start the loop
		while (have_posts()) : the_post();
	?>
		<?php
			// Insert the post content
			get_template_part( 'content' );
		?>
	<?php endwhile; ?>


	<?php
		// Previous/next page navigation
		// get_template_part( 'nav-page', 'Page Navigation' );

		// // Show link to archives page
		// $archives_page = get_posts(array(
		// 	'post_type' => 'page',
		// 	'meta_key' => '_wp_page_template',
		// 	'meta_value' => 'archives.php',
		// 	'number' => 1,
		// ));
		// $options = keel_get_theme_options();
		// $archives_link = empty( $archives_page[0] ) ? '' : '<a href="' . get_permalink( $archives_page[0]->ID ) . '">' . stripslashes( $options['resources_link'] ) . '</a>';
		// // if ( !empty( $archives_page[0] ) ) {
		// // 	echo '<p><a href="' . get_permalink( $archives_page[0]->ID ) . '">' . stripslashes( $options['resources_link'] ) . '</a></p>';
		// // }

		// echo '<p></p>';
	?>


<?php else : ?>
	<?php
		// If no content, include the "No post found" template
		get_template_part( 'no-posts', 'No Posts Template' );
	?>
<?php endif; ?>


<?php get_footer(); ?>