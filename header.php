<?php

/**
 * header.php
 * Template for header content.
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php if ( is_home () ) : ?><meta name="description" content="<?php bloginfo('description'); ?>"><?php endif; ?>

		<!-- Mobile Screen Resizing -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Icons: place in the root directory -->
		<!-- https://github.com/audreyr/favicon-cheat-sheet -->
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/favicon.ico">
		<link rel="icon" sizes="16x16 32x32" href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/favicon.ico">

		<!-- Feeds & Pings -->
		<link rel="alternate" type="application/rss+xml" title="<?php printf( __( '%s RSS Feed', 'keel' ), get_bloginfo( 'name' ) ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php wp_head(); ?>

	</head>

	<body>
		<div data-sticky-wrap>

			<!-- Old Browser Warning -->
			<!--[if lt IE 9]>
				<section>
					<p>Did you know that your web browser is a bit old? Some of the content on this site might not work right as a result. <a href="http://whatbrowser.org">Upgrade your browser</a> for a faster, safer, and better web experience.</p>
				</section>
			<![endif]-->

			<!-- Skip link for better accessibility -->
			<!-- http://cferdinandi.github.io/kraken/overrides.html#visibility -->
			<a class="screen-reader screen-reader-focusable" href="#main">Skip to main content</a>

			<?php

				// Get site navigation
				get_template_part( 'nav-main', 'Site Navigation' );

				// Get site hero
				get_template_part( 'hero', 'Hero header' );

			?>

			<?php

				// Variables
				global $post;
				$keel_page_width_class = 'container';

				// Get width setting
				$keel_get_page_width = get_post_meta( $post->ID, 'keel_page_width', true );
				if ( $keel_get_page_width === 'wide' || is_home() || is_single() ) {
					$keel_page_width_class .= ' container-large';
				}
				if ( $keel_get_page_width === 'diy' ) {
					$keel_page_width_class = '';
				}
			?>

			<main class="<?php echo $keel_page_width_class; ?>" id="main">

				<?php if ( is_home() || is_single() || is_page_template( 'archives.php' ) ) : ?>
				<div class="row">
					<div class="grid-two-thirds">
				<?php endif; ?>
