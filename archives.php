<?php

/**
 * Template Name: Archives
 * Template for blog archives.
 */

get_header(); ?>

<?php
	$archives = new WP_Query(
		array(
			'posts_per_page' => -1
		)
	);
?>

<?php if ($archives->have_posts()) : ?>

	<?php
		// Start the loop
		while ($archives->have_posts()) : $archives->the_post();
	?>

		<?php
			// Insert the post content
			get_template_part( 'content', 'Post Content' );
		?>

	<?php endwhile; ?>

<?php endif; ?>

<?php wp_reset_postdata(); ?>


<?php get_footer(); ?>