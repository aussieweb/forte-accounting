<?php

/**
 * content.php
 * Template for post and page content.
 */

?>

<article>

	<?php if ( !is_page() || is_page( 'all-resources' ) ) : ?>
		<header>
			<?php
				/**
				 * Headers
				 * Unlinked h1 for pages and invidual blog posts.
				 * Linked h1 for collections of posts.
				 */
			?>
			<h1 class="margin-bottom-small">
				<?php if ( is_single() ) : ?>
					<?php the_title(); ?>
				<?php else : ?>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				<?php endif; ?>
			</h1>

			<?php
				/**
				 * Add meta data for blog posts.
				 * 1. Published date
				 * 2. Author
				 * 3. Number of comments
				 * 4. Quick edit link
				 */
			?>
			<aside>
				<p class="text-muted">
					<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_time( 'F j, Y' ) ?></time> by <?php the_author(); ?>
					<?php edit_post_link( __( 'Edit', 'keel' ), ' / ', '' ); ?>
				</p>
			</aside>
		</header>
	<?php endif; ?>

	<?php
		// The page or post content
		if ( is_page( 'all-resources' ) ) {
			the_excerpt();
		} else {
			the_content( '<p>' . __( 'Read More...', 'keel' ) . '</p>' );
		}
	?>

	<?php if ( is_page() && !is_page( 'all-resources' ) ) : ?>
		<?php
			// Add link to edit pages
			edit_post_link( __( 'Edit', 'keel' ), '<p>', '</p>' );
		?>
	<?php endif; ?>

	<?php
		if ( is_home() ) {
			// Show link to comments and archives page
			$comments_count = keel_just_comments_count();
			$archives_page = get_posts(array(
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => 'archives.php',
				'number' => 1,
			));
			$options = keel_get_theme_options();

			$archives_link = empty( $archives_page[0] ) ? '' : ' <a class="float-right" href="' . get_permalink( $archives_page[0]->ID ) . '">' . stripslashes( $options['resources_link'] ) . '</a>';

			$comments_link = $comments_count === 0 ? get_permalink() . '#respond' : get_comments_link();
			$comments_link_text = 'Leave a Comment';
			if ( $comments_count === 1 ) { $comments_link_text = '1 Comment'; }
			if ( $comments_count > 1 ) { $comments_link_text = $comments_count . ' Comments'; }

			echo '<p><a href="' . $comments_link . '">' . $comments_link_text . '</a>' . $archives_link . '</p>';
		}
	?>

	<?php if ( is_single() ) : ?>
		<?php
			// Add comments template to blog posts
			comments_template();
		?>
	<?php endif; ?>

	<?php
		// If this is not the last post on the page, insert a divider
		global $archives;

		if ( is_page_template( 'archives.php' ) ) {
			if ( !keel_is_last_post( $archives ) ) {
				echo '<hr>';
			}
		} elseif ( !keel_is_last_post( $wp_query ) ) {
			echo '<hr>';
		}
	?>

</article>