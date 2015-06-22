<?php

/**
 * sidebar.php
 * Template for the sidebar.
 */

?>

	<?php
		$options = keel_get_theme_options();
		$rss_text = empty( $options['updates_rss'] ) ? 'Sign up by RSS' : esc_attr( $options['updates_rss'] );
		$rss_link = '<a href="' . get_bloginfo('rss2_url') . '">' . $rss_text . '</a>';
	?>

	<div class="grid-third">
		<?php echo stripslashes( $options['resources'] ); ?>

		<div class="margin-bottom-large">
			<h2 class="margin-bottom-small">
				<?php
					if ( empty( $options['updates_header'] ) ) {
						echo 'Free Updates';
					} else {
						echo $options['updates_header'];
					}
				?>
			</h2>
			<?php if ( !empty($options['updates_url']) && !empty($options['updates_spam']) ) : ?>
				<?php
					if ( !empty( $options['updates_text'] ) ) {
						echo '<p class="margin-bottom-small">' . $options['updates_text'] . '</p>';
					}
				?>
				<div id="mc_embed_signup">
					<form action="<?php echo esc_url( $options['updates_url'] ); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate margin-bottom-small" target="_blank" novalidate>
						<div id="mc_embed_signup_scroll">
							<div class="mc-field-group">
								<label class="screen-reader" for="mce-EMAIL">Email Address</label>
								<input type="email" value="" name="EMAIL" class="required email margin-bottom-small" placeholder="your@email.com" id="mce-EMAIL">
							</div>
							<div class="screen-reader"><input type="text" name="<?php echo esc_attr( $options['updates_spam'] ); ?>" tabindex="-1" value=""></div>
							<div id="mce-responses">
								<div class="margin-bottom response" id="mce-error-response" style="display:none"></div>
								<div class="margin-bottom response" id="mce-success-response" style="display:none"></div>
							</div>
							<div>
								<button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn">
									<?php
										if ( empty( $options['updates_submit'] ) ) {
											echo 'Sign Up';
										} else {
											echo esc_attr( $options['updates_submit'] );
										}
									?>
								</button>
								<?php echo $rss_link; ?>
							</div>
						</div>
					</form>
				</div>
			<?php else : ?>
				<p><?php echo $rss_link; ?></p>
			<?php endif; ?>
		</div>
	</div>