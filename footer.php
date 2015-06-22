<?php

/**
 * footer.php
 * Template for footer content.
 */

?>

				<?php if ( is_home() || is_single() || is_page_template( 'archives.php' ) ) : ?>
					</div>

					<?php get_sidebar(); ?>

				</div>
				<?php endif; ?>

			</main><!-- /#main -->
		</div>

		<footer class="bg-muted padding-top padding-bottom border-top" data-sticky-footer>
			<div class="container container-large">
				<div class="row">
					<div class="grid-half grid-flip">
						<?php
							$options = keel_get_theme_options();
							echo stripslashes( $options['sidebar_main'] );
						?>
					</div>
					<div class="grid-half">
						<p class="text-muted text-small">&copy; Copyright <?php echo date( 'Y' ); ?> <?php echo get_bloginfo( 'name' ); ?>. All Rights Reserved. <a href="http://gomakethings.com">Website by Go Make Things.</a></p>
					</div>
				</div>
			</div>
		</footer>


		<?php wp_footer(); ?>

	</body>
</html>