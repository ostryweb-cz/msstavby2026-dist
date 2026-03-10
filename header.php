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

		<header id="masthead" class="site-header bg-white fixed-top">
			<div class="container h-100">
				<div class="d-flex justify-content-between align-items-center h-100">
					<div class="site-branding my-1">
					<?php
					if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
						the_custom_logo();
					} elseif ( file_exists( get_template_directory() . '/images/msstavby.gif' ) ) {
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-decoration-none">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/images/msstavby.gif' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="160" height="89" />';
						echo '</a>';
					} else {
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-decoration-none text-dark text-decoration-none">';
						echo '<h1 class="site-title h5 m-0">' . esc_html( bloginfo( 'name' ) ) . '</h1>';
						echo '</a>';
					}
					?>
						<?php admin_comment('.site-branding'); ?>

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
						
						<div class="header-actions d-flex align-items-center gap-2">
						<?php admin_comment('Search Toggle (always visible)'); ?>
						<a href="#" id="searchbutt" title="vyhledávání" class="p-2 header-icon" aria-label="Search">
							<i class="bi bi-search"></i>
						</a>
							
						<?php admin_comment('Login/Logout Link'); ?>
						<?php
						$is_logged_in = current_user_can( 'level_0' );
						$account_url = $is_logged_in ? wp_logout_url( $_SERVER['REQUEST_URI'] ) : 'https://www.msstavby.cz/prihlaseni/';
						$account_title = $is_logged_in ? 'odhlásit' : 'přihlásit';
						$account_aria = $is_logged_in ? 'Logout' : 'Login';
						$account_icon = $is_logged_in ? 'person-fill' : 'person';
						?>
						<a href="<?php echo esc_url( $account_url ); ?>" class="p-2 header-icon" aria-label="<?php echo esc_attr( $account_aria ); ?>" title="<?php echo esc_attr( $account_title ); ?>">
							<i class="bi bi-<?php echo esc_attr( $account_icon ); ?>"></i>
						</a>
						</div>
				</div>
			</div>
			
		<?php admin_comment('Search Form (hidden by default)'); ?>
		<div id="searchWrap">
				<div class="container py-3">
					<?php get_search_form(); ?>
				</div>
			</div>
		<?php admin_comment('#masthead'); ?>
		</header>
