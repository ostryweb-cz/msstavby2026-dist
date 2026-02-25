<?php
/**
 * The header for our theme
 *
 * @package msstavby
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'msstavby' ); ?></a>

		<header id="masthead" class="site-header bg-white shadow-sm py-3 fixed-top" style="min-height: 72px;">
			<div class="container">
				<div class="d-flex justify-content-between align-items-center">
					<div class="site-branding">
						<?php
					if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
						the_custom_logo();
					} else {
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-decoration-none text-dark text-decoration-none">';
						echo '<h1 class="site-title h5 m-0">' . esc_html( bloginfo( 'name' ) ) . '</h1>';
						echo '</a>';
					}
					?>
					</div><!-- .site-branding -->

					<div class="d-flex align-items-center gap-3">
						<nav class="navbar navbar-expand-md">
							<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryMenu" aria-controls="primaryMenu" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse" id="primaryMenu">
								<?php
								wp_nav_menu( array(
									'theme_location' => 'primary',
									'menu_class'     => 'navbar-nav me-auto mb-2 mb-md-0',
									'container'      => false,
									'fallback_cb'    => false,
									'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								) );
								?>
							</div>
						</nav>
						
						<div class="d-flex align-items-center gap-2">
							<!-- Search Toggle (always visible) -->
							<button class="btn btn-link p-2" id="search-toggle" aria-label="Search">
								<i class="bi bi-search"></i>
							</button>
							
						<!-- Login/Logout Link -->
						<?php
						if ( is_user_logged_in() ) {
							$logout_url = wp_logout_url( home_url() );
						?>
						<a href="<?php echo esc_url( $logout_url ); ?>" class="btn btn-link p-2" aria-label="Logout">
							<i class="bi bi-person-fill"></i>
						</a>
						<?php } else { ?>
						<a href="<?php echo esc_url( wp_login_url( home_url() ) ); ?>" class="btn btn-link p-2" aria-label="Login">
							<i class="bi bi-person"></i>
						</a>
						<?php } ?>
						</div>
				</div>
			</div>
			
			<!-- Search Form (hidden by default) -->
			<div class="collapse" id="search-form">
				<div class="container py-3">
					<?php get_search_form(); ?>
				</div>
			</div>
		</header><!-- #masthead -->
