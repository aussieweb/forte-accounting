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
			<?php $options = keel_get_theme_options(); ?>
			<div class="container container-large">
				<div class="row">
					<div class="grid-half grid-flip">
						<?php echo stripslashes( $options['sidebar_main'] ); ?>
					</div>
					<div class="grid-half">
						<?php
							if ( empty( $options['footer_copyright'] ) ) {
								?>
								<p class="text-muted text-small">&copy; Copyright <?php echo date( 'Y' ); ?> <?php echo get_bloginfo( 'name' ); ?>. All Rights Reserved. Intuit and QuickBooks are registered trademarks of Intuit Inc. <a href="http://gomakethings.com">Website by Go Make Things.</a></p>';
								<?php
							} else {
								$year = date( 'Y' );
								$name = get_bloginfo( 'name' );
								echo stripslashes(str_replace(
									array( '{{year}}', '{{name}}' ),
									array( $year, $name ),
									$options['footer_copyright']
								));
							}
						?>
					</div>
				</div>
			</div>
		</footer>


		<?php wp_footer(); ?>

	</body>
</html>