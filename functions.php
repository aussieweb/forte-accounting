<?php

/**
 * functions.php
 * For modifying and expanding core WordPress functionality.
 */


	/**
	 * Load theme files.
	 */
	function keel_load_theme_files() {
		$keel_theme = wp_get_theme();
		if ( isset($_COOKIE['fullCSS']) && $_COOKIE['fullCSS'] === 'true' ) {
			wp_enqueue_style( 'keel-theme-styles', get_template_directory_uri() . '/dist/css/main.min.' . $keel_theme->get( 'Version' ) . '.css', null, null, 'all' );
		}
	}
	add_action('wp_enqueue_scripts', 'keel_load_theme_files');



	/**
	 * Include feature detection scripts inline in the header
	 */
	function keel_initialize_theme_inline_header() {
		$keel_theme = wp_get_theme();

		// If stylesheet is in browser cache, load it the traditional way
		if ( isset($_COOKIE['fullCSS']) && $_COOKIE['fullCSS'] === 'true' ) {
		?>
			<script>
				<?php echo file_get_contents( get_template_directory_uri() . '/dist/js/detects.min.' . $keel_theme->get( 'Version' ) . '.js' ); ?>
			</script>
		<?php

		// Otherwise, inline critical CSS and load full stylesheet asynchronously
		} else {
		?>
			<script>
				<?php echo file_get_contents( get_template_directory_uri() . '/dist/js/detects.min.' . $keel_theme->get( 'Version' ) . '.js' ); ?>
				var keelCSS = loadCSS('<?php echo get_template_directory_uri() . "/dist/css/main.min." . $keel_theme->get( "Version" ) . ".css"; ?>');
				onloadCSS( keelCSS, function() {
					var expires = new Date(+new Date + (7 * 24 * 60 * 60 * 1000)).toUTCString();
					document.cookie = 'fullCSS=true; expires=' + expires;
				});
			</script>
			<style>
				<?php echo file_get_contents( get_template_directory_uri() . '/dist/css/critical.min.' . $keel_theme->get( 'Version' ) . '.css' ); ?>
			</style>
		<?php
		}
		?>
			<script>
				loadCSS( '//fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic|Open+Sans:400italic,400,700' );
			</script>
			<meta name="google-site-verification" content="HI4TelPfWTXsl6_R4JJWjv_EAXPAYeeGgYCrs2RwYE4" />
		<?php
	}
	add_action('wp_head', 'keel_initialize_theme_inline_header', 30);



	/**
	 * Include script inits inline in the footer
	 */
	function keel_initialize_theme_inline_footer() {
		$keel_theme = wp_get_theme();

		// If stylesheet is in browser cache, don't set noscript fallback
		if ( isset($_COOKIE['fullCSS']) && $_COOKIE['fullCSS'] === 'true' ) {
		?>
			<noscript>
				<link href='//fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic|Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
			</noscript>
		<?php

		// Otherwise, set a noscript fallback
		} else {
		?>
			<noscript>
				<link href='fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic|Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
				<link href='<?php echo get_template_directory_uri() . "/dist/css/main.min." . $keel_theme->get( "Version" ) . ".css"; ?>' rel='stylesheet' type='text/css'>
			</noscript>
		<?php
		}

		// Asynchronously load JavaScript if browser passes mustard test
		?>
			<script>
				<?php echo file_get_contents( get_template_directory_uri() . '/dist/js/loadJS.min.' . $keel_theme->get( 'Version' ) . '.js' ); ?>
				if ( !!document.querySelector && !!window.addEventListener ) {
					loadJS('<?php echo get_template_directory_uri() . "/dist/js/main.min." . $keel_theme->get( "Version" ) . ".js"; ?>');
				}
			</script>

			<?php if ( is_home('newsletter') || is_single() || is_page_template( 'archives.php' ) ) : ?>
				<script>var mc_custom_error_style = '#mc_embed_signup div.mce_inline_error { display: block; font-style: italic; margin-bottom: 1em; } input.mce_inline_error { margin-bottom: 0.5em; } #mce-responses { font-style: italic; }';</script>
				<script src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
				<script>
					(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[1]='FNAME';ftypes[1]='text';fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);
				</script>
			<?php endif; ?>
		<?php
	}
	add_action('wp_footer', 'keel_initialize_theme_inline_footer', 30);



	/**
	 * Replace RSS links with Feedburner url
	 * @link http://codex.wordpress.org/Using_FeedBurner
	 */
	function keel_custom_rss_feed( $output, $feed ) {
		$options = keel_get_theme_options();
		if ( strpos( $output, 'comments' ) || empty( $options['feedburner_url'] ) ) {
			return $output;
		}
		return esc_url( $options['feedburner_url'] );
	}
	add_action( 'feed_link', 'keel_custom_rss_feed', 10, 2 );



	/**
	 * Add a shortcode for the search form
	 * @return string Markup for search form
	 */
	function keel_wpsearch() {
		return get_search_form();
	}
	add_shortcode( 'searchform', 'keel_wpsearch' );



	/**
	 * Replace the default password-protected post messaging with custom language
	 * @return string Custom language
	 */
	function keel_post_password_form() {
		global $post;
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		$form =
			'<form class="text-center" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post"><p>' . __( 'This is a password protected post.', 'keel' ) . '</p><label class="screen-reader" for="' . $label . '">' . __( 'Password', 'keel' ) . '</label><input id="' . $label . '" name="post_password" type="password"><input type="submit" name="Submit" value="' . __( 'Submit', 'keel' ) . '"></form>';
		return $form;
	}
	add_filter( 'the_password_form', 'keel_post_password_form' );



	/**
	 * Customize the `wp_title` method
	 * @param  string $title The page title
	 * @param  string $sep   The separator between title and description
	 * @return string        The new page title
	 */
	function keel_pretty_wp_title( $title, $sep ) {

		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name
		$title .= get_bloginfo( 'name' );

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'keel' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'keel_pretty_wp_title', 10, 2 );



	/**
	 * Override default the_excerpt length
	 * @param  number $length Default length
	 * @return number         New length
	 */
	function keel_excerpt_length( $length ) {
		return 35;
	}
	add_filter( 'excerpt_length', 'keel_excerpt_length', 999 );



	/**
	 * Override default the_excerpt read more string
	 * @param  string $more Default read more string
	 * @return string       New read more string
	 */
	function keel_excerpt_more( $more ) {
		return '... <a href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'keel') . '</a>';
	}
	add_filter( 'excerpt_more', 'keel_excerpt_more' );



	/**
	 * Sets max allowed content width
	 * Deliberately large to prevent pixelation from content stretching
	 * @link http://codex.wordpress.org/Content_Width
	 */
	if ( !isset( $content_width ) ) {
		$content_width = 1240;
	}



	/**
	 * Registers navigation menus for use with wp_nav_menu function
	 * @link http://codex.wordpress.org/Function_Reference/register_nav_menus
	 */
	function keel_register_menus() {
		register_nav_menus(
			array(
				'primaruy' => __( 'Primary Menu' ),
				'secondary' => __( 'Secondary Menu' )
			)
		);
	}
	add_action( 'init', 'keel_register_menus' );



	/**
	 * Adds support for featured post images
	 * @link http://codex.wordpress.org/Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );



	/**
	 * Adds support for custom header images
	 * @link http://codex.wordpress.org/Custom_Headers
	 */
	// add_theme_support( 'custom-header' );



	/**
	 * Disable WordPress auto-formatting
	 * Disabled by default. Uncomment add_action to enable
	 */
	function keel_remove_wpautop() {
		remove_filter('the_content', 'wpautop');
	}
	// add_action( 'pre_get_posts', 'keel_remove_wpautop' );



	/**
	 * Display all posts instead of a limited number
	 * @param  array $query The WordPress post query
	 */
	function keel_get_all_posts( $query ) {
		$query->set( 'posts_per_page', '-1' );
	}
	// add_action( 'pre_get_posts', 'keel_get_all_posts' );



	/**
	 * Custom comment callback for wp_list_comments used in comments.php
	 * @param  object $comment The comment
	 * @param  object $args Comment settings
	 * @param  integer $depth How deep to nest comments
	 * @return string Comment markup
	 * @link http://codex.wordpress.org/Function_Reference/wp_list_comments
	 */
	function keel_comment_layout($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' === $args['style'] ) {
			$tag = 'div';
		} else {
			$tag = 'li';
		}
	?>

		<<?php echo $tag ?> <?php if ( $depth > 1 ) { echo 'class="comment-nested"'; } ?> id="comment-<?php comment_ID() ?>">

			<article>

				<?php if ($comment->comment_approved == '0') : // If the comment is held for moderation ?>
					<p><em><?php _e( 'Your comment is being held for moderation.', 'keel' ) ?></em></p>
				<?php endif; ?>

				<header>
					<figure>
						<?php if ( $args['avatar_size'] !== 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
					</figure>
					<h3>
						<?php comment_author_link() ?>
					</h3>
					<aside>
						<time datetime="<?php comment_date( 'Y-m-d' ); ?>" pubdate><?php comment_date('F jS, Y') ?></time>
						<?php edit_comment_link('Edit', ' / ', ''); ?>
					</aside>
				</header>

				<?php comment_text(); ?>
				<?php
					/**
					 * Add inline reply link.
					 * Only displays if nested comments are enabled.
					 */
					comment_reply_link( array_merge(
						$args,
						array(
							'add_below' => 'comment',
							'depth' => $depth,
							'max_depth' => $args['max_depth'],
							'before' => '<p>',
							'after' => '</p>'
						)
					) );
				?>

			</article>

	<?php
	}



	/**
	 * Custom implementation of comment_form
	 * @return string Markup for comment form
	 */
	function keel_comment_form() {

		$commenter = wp_get_current_commenter();
		global $user_identity;

		$must_log_in =
			'<p>' .
				sprintf(
					__( 'You must be <a href="%s">logged in</a> to post a comment.' ),
					wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
				) .
			'</p>';

		$logged_in_as =
			'<p>' .
				sprintf(
					__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s">Log out.</a>' ),
					admin_url( 'profile.php' ),
					$user_identity,
					wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
				) .
			'</p>';

		$notes_before = '';
		$notes_after = '';

		$field_author =
			'<div>' .
				'<label for="author">' . __( 'Name' ) . '</label>' .
				'<input type="text" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" required>' .
			'</div>';

		$field_email =
			'<div>' .
				'<label for="email">' . __( 'Email' ) . '</label>' .
				'<input type="email" name="email" id="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" required>' .
			'</div>';

		$field_url =
			'<div>' .
				'<label for="url">' . __( 'Website (optional)' ) . '</label>' .
				'<input type="url" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '">' .
			'</div>';

		$field_comment =
			'<div>' .
				'<textarea name="comment" id="comment" required></textarea>' .
			'</div>';

		$args = array(
			'title_reply' => __( 'Leave a Comment' ),
			'title_reply_to' => __( 'Reply to %s' ),
			'cancel_reply_link' => __( '[Cancel]' ),
			'label_submit' => __( 'Submit Comment' ),
			'comment_field' => $field_comment,
			'must_log_in' => $must_log_in,
			'logged_in_as' => $logged_in_as,
			'comment_notes_before' => $notes_before,
			'comment_notes_after' => $notes_after,
			'fields' => apply_filters(
				'comment_form_default_fields',
				array(
					'author' => $field_author,
					'email' => $field_email,
					'url' => $field_url
				)
			),
		);

		return comment_form( $args );

	}



	/**
	 * Add script for threaded comments if enabled
	 */
	if ( is_single() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script( 'comment-reply' );
	}



	/**
	 * Deregister JetPack's devicepx.js script
	 */
	function keel_dequeue_devicepx() {
		wp_dequeue_script( 'devicepx' );
	}
	add_action( 'wp_enqueue_scripts', 'keel_dequeue_devicepx', 20 );



	/**
	 * Remove Jetpack front-end styles
	 * @todo Remove once Jetpack glitch fixed
	 */
	add_filter( 'jetpack_implode_frontend_css', '__return_false' );



	/**
	 * Remove empty paragraphs created by wpautop()
	 * @author Ryan Hamilton
	 * @link https://gist.github.com/Fantikerz/5557617
	 */
	function keel_remove_empty_p( $content ) {
		$content = force_balance_tags( $content );
		$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		$content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		return $content;
	}
	add_filter('the_content', 'keel_remove_empty_p', 20, 1);



	/**
	 * Allow new content types in posts
	 */
	$allowedposttags['svg']['xmlns'] = true;
	$allowedposttags['svg']['class'] = true;
	$allowedposttags['svg']['id'] = true;
	$allowedposttags['svg']['viewbox'] = true;
	$allowedposttags['path']['d'] = true;
	$allowedposttags['a']['data-tab'] = true;
	// add_filter( 'user_can_richedit', '__return_false' );

	function keel_load_svg( $atts ) {

		// Variables
		$exports = '';
		$tags = '';

		// Get SVG and add classes
	    if ( $atts['tag'] === 'book' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 512 512"><path d="M508.452 163.33C482.904 131.667 435.217 112 384 112s-98.904 19.668-124.452 51.33c-2.296 2.844-3.548 6.39-3.548 10.046 0-3.656-1.252-7.202-3.548-10.047C226.905 131.667 179.218 112 128 112S29.095 131.668 3.548 163.33C1.252 166.173 0 169.72 0 173.375v320c0 6.782 4.277 12.828 10.672 15.087s13.52.24 17.78-5.04C48.072 479.107 86.218 464 128 464s79.927 15.106 99.548 39.424c3.098 3.84 7.71 5.954 12.455 5.954h31.994c4.745 0 9.358-2.115 12.455-5.954C304.072 479.106 342.218 464 384 464s79.926 15.106 99.548 39.424c4.26 5.277 11.383 7.297 17.78 5.04S512 500.157 512 493.375v-320c0-3.656-1.252-7.202-3.548-10.047zM224 458.29C197.99 441.642 163.86 432 128 432s-69.99 9.642-96 26.29V179.34C52.452 157.427 88.61 144 128 144s75.548 13.428 96 35.34v278.95zm256 0C453.988 441.64 419.86 432 384 432s-69.988 9.642-96 26.29V179.34c20.452-21.912 56.61-35.34 96-35.34s75.548 13.427 96 35.34v278.95zM320 208h128v32H320v-32zM320 272h128v32H320v-32zM320 336h96v32h-96v-32zM64 208h128v32H64v-32zM64 272h128v32H64v-32zM64 336h96v32H64v-32z"/></svg>';
	    }

	    if ( $atts['tag'] === 'pencil' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 512 512"><path d="M368.5 302.063V456.75H87.25V175.5h154.688l56.25-56.25h-225C49.985 119.25 31 138.236 31 161.44v309.374c0 23.203 18.984 42.188 42.188 42.188h309.375c23.202 0 42.188-18.985 42.188-42.188v-225l-56.25 56.25zM410.688 63L143.5 330.188V400.5h70.313L481 133.314C481 91.125 452.877 63 410.69 63zM213.813 344.25l-21.094-21.094 210.937-210.938 21.094 21.094L213.814 344.25z"/></svg>';
	    }

	    if ( $atts['tag'] === 'money' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 544 512"><path d="M224 272h32v32h-32v-32zM0 176v288h544V176H0zm96 256H32v-64h32v32h32v32zm0-192H64v32H32v-64h64v32zm240 64c8.837 0 16 7.163 16 16v64c0 8.837-7.163 16-16 16h-48v16c0 8.837-7.163 16-16 16s-16-7.163-16-16v-16h-48c-8.836 0-16-7.163-16-16s7.164-16 16-16h48v-32h-48c-8.836 0-16-7.163-16-16v-64c0-8.836 7.164-16 16-16h48v-16c0-8.836 7.163-16 16-16s16 7.164 16 16v16h48c8.837 0 16 7.164 16 16s-7.163 16-16 16h-48v32h48zm176 128h-64v-32h32v-32h32v64zm0-160h-32v-32h-32v-32h64v64zM288 336h32v32h-32v-32z"/></svg>';
	    }

	    if ( $atts['tag'] === 'linkedin' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 32 32"><path d="M26.625 0H5.375C2.42 0 0 2.42 0 5.375v21.25C0 29.58 2.42 32 5.375 32h21.25C29.58 32 32 29.58 32 26.625V5.375C32 2.42 29.58 0 26.625 0zM12 26H8V12h4v14zm-2-16c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm16 16h-4v-8c0-1.105-.895-2-2-2s-2 .895-2 2v8h-4V12h4v2.483C18.825 13.35 20.086 12 21.5 12c2.485 0 4.5 2.24 4.5 5v9z"/></svg>';
	    }

	    if ( $atts['tag'] === 'facebook' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 32 32"><path d="M26.667 0H5.333C2.388 0 0 2.388 0 5.334v21.332C0 29.612 2.387 32 5.333 32H16V18h-4v-4h4v-3c0-2.76 2.24-5 5-5h5v4h-5c-.552 0-1 .448-1 1v3h5.5l-1 4H20v14h6.667C29.612 32 32 29.612 32 26.666V5.334C32 2.388 29.613 0 26.667 0z"/></svg>';
	    }

	    if ( $atts['tag'] === 'google' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 32 32"><path d="M.025 27.177c-.008-.08-.014-.158-.018-.238.004.08.01.158.018.237zm7.347-9.516c2.875.087 4.804-2.896 4.308-6.66S8.45 4.21 5.574 4.125C2.698 4.04.77 6.922 1.266 10.688c.496 3.765 3.23 6.887 6.106 6.973zM32 8V5.334C32 2.4 29.6 0 26.667 0H5.334C2.45 0 .084 2.32.004 5.185 1.828 3.58 4.358 2.238 6.97 2.238h11.163L15.635 4.35h-3.54c2.348.9 3.6 3.63 3.6 6.43 0 2.35-1.308 4.374-3.154 5.812-1.8 1.403-2.142 1.99-2.142 3.184 0 1.018 1.93 2.75 2.938 3.462 2.95 2.08 3.904 4.01 3.904 7.233 0 .514-.064 1.027-.19 1.53h9.617C29.6 32 32 29.604 32 26.668V10h-6v6h-2v-6h-6V8h6V2h2v6h6zM5.81 23.936c.674 0 1.293-.018 1.935-.018-.848-.823-1.52-1.83-1.52-3.074 0-.738.236-1.448.567-2.08-.337.025-.68.032-1.035.032-2.324 0-4.297-.752-5.756-1.995v8.406c1.67-.793 3.654-1.27 5.81-1.27zm-5.703 3.79c-.035-.17-.06-.343-.08-.52.02.177.046.35.08.52zm14.126 2.05c-.47-1.838-2.14-2.75-4.465-4.36-.846-.274-1.778-.435-2.778-.445-2.8-.03-5.41 1.093-6.882 2.763C.606 30.16 2.765 32 5.334 32h8.95c.058-.348.085-.707.085-1.076 0-.392-.05-.775-.138-1.148z"/></svg>';
	    }

	    if ( $atts['tag'] === 'phone' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 32 32"><path d="M30.19 5.526c-.89-.75-2.106-1.405-3.618-1.945C23.717 2.563 19.962 2 16 2c-3.963 0-7.718.56-10.572 1.58-1.512.54-2.73 1.195-3.618 1.946C.314 6.79 0 8.113 0 9v2c0 .53.21 1.04.586 1.414l1 1C1.968 13.796 2.48 14 3 14c.25 0 .503-.047.743-.143l5-2C9.503 11.553 10 10.817 10 10V6.51c1.83-.333 3.882-.51 6-.51s4.17.177 6 .51V10c0 .818.498 1.553 1.257 1.857l5 2c.24.096.493.143.742.143.52 0 1.03-.203 1.414-.586l1-1C31.79 12.04 32 11.53 32 11V9c0-.888-.314-2.21-1.81-3.474zM29.863 24.88c-.03-.108-.763-2.652-2.012-5.238C26.045 15.898 23 14 19 14h-6c-4 0-7.044 1.898-8.85 5.642-1.25 2.586-1.982 5.13-2.012 5.237-.375 1.312-.17 2.62.562 3.592S4.635 30 6 30h20c1.365 0 2.568-.557 3.3-1.528s.938-2.28.563-3.593zM16 26c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z" /></svg>';
	    }

	    if ( $atts['tag'] === 'email' ) {
	    	$exports = '<svg xmlns="http://www.w3.org/2000/svg" class="' . $atts['class'] . '" viewBox="0 0 32 32"><path d="M29 4H3C1.35 4 0 5.35 0 7v20c0 1.65 1.35 3 3 3h26c1.65 0 3-1.35 3-3V7c0-1.65-1.35-3-3-3zM12.46 17.2L4 23.79V8.112l8.46 9.086zM5.513 8h20.976L16 15.875 5.512 8zm7.278 9.553L16 21l3.21-3.447L25.79 26H6.21l6.58-8.447zm6.75-.354L28 8.112V23.79l-8.46-6.59z" /></svg>';
	    }

	    return $exports;
	}
	add_shortcode( 'svg', 'keel_load_svg' );


	/**
	 * Get number of comments (without trackbacks or pings)
	 * @return integer Number of comments
	 */
	function keel_just_comments_count() {
		global $post;
		return count( get_comments( array( 'type' => 'comment', 'post_id' => $post->ID ) ) );
	}



	/**
	 * Get number of trackbacks
	 * @return integer Number of trackbacks
	 */
	function keel_trackbacks_count() {
		global $post;
		return count( get_comments( array( 'type' => 'trackback', 'post_id' => $post->ID ) ) );
	}



	/**
	 * Get number of pings
	 * @return integer Number of pings
	 */
	function keel_pings_count() {
		global $post;
		return count( get_comments( array( 'type' => 'pingback', 'post_id' => $post->ID ) ) );
	}



	/**
	 * Check if more than one page of content exists
	 * @return boolean True if content is paginated
	 */
	function keel_is_paginated() {
		global $wp_query;

		if ( $wp_query->max_num_pages > 1 ) {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Check if post is the last in a set
	 * @param  object  $wp_query WPQuery object
	 * @return boolean           True if is last post
	 */
	function keel_is_last_post($wp_query) {
		$post_current = $wp_query->current_post + 1;
		$post_count = $wp_query->post_count;
		if ( $post_current == $post_count ) {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Print a pre formatted array to the browser - useful for debugging
	 * @param array $array Array to print
	 * @author 	Keir Whitaker
	 * @link https://github.com/viewportindustries/starkers/
	 */
	function keel_print_a( $a ) {
		print( '<pre>' );
		print_r( $a );
		print( '</pre>' );
	}



	/**
	 * Log PHP in the JavaScript console
	 */
	function keel_console_log($name, $data = NULL, $jsEval = FALSE) {

		if (! $name) return false;

		$isevaled = false;
		$type = ( $data || gettype($data) ) ? 'Type: ' . gettype($data) : '';

		if ( $jsEval && ( is_array( $data ) || is_object( $data ) ) ) {
			$data = 'eval(' . preg_replace('#[\s\r\n\t\0\x0B]+#', '', json_encode($data)) . ')';
			$isevaled = true;
		} else {
			$data = json_encode($data);
		}

		// Sanitalize
		$data = $data ? $data : '';
		$search_array = array("#'#", '#""#', "#''#", "#\n#", "#\r\n#");
		$replace_array = array('"', '', '', '\\n', '\\n');
		$data = preg_replace($search_array,  $replace_array, $data);
		$data = ltrim(rtrim($data, '"'), '"');
		$data = $isevaled ? $data : ($data[0] === "'") ? $data : "'" . $data . "'";

		$js =
			"<script>
				// fallback - to deal with IE (or browsers that don't have console)
				if (! window.console) console = {};
				console.log = console.log || function(name, data){};
				// end of fallback

				console.log('$name');
				console.log('------------------------------------------');
				console.log('$type');
				console.log($data);
				console.log('\\n');
			</script>";

			echo $js;
	}



	/**
	 * Pass in a path and get back the page ID
	 * @param  string $path The URL of the page
	 * @return integer Page or post ID
	 * @author Keir Whitaker
	 * @link https://github.com/viewportindustries/starkers/
	 */
	function keel_get_page_id_from_path( $path ) {
		$page = get_page_by_path( $path );
		if( $page ) {
			return $page->ID;
		} else {
			return null;
		};
	}



	/**
	 * Includes
	 */
	require_once( dirname( __FILE__) . '/includes/keel-theme-options.php' );
	require_once( dirname( __FILE__) . '/includes/keel-page-subtitles.php' );
	require_once( dirname( __FILE__) . '/includes/keel-page-call-to-action.php' );
	require_once( dirname( __FILE__) . '/includes/keel-set-page-width.php' );