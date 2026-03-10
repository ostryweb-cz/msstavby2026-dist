<?php
/**
 * The footer for our theme
 *
 * @package msstavby
 */

?>
		<?php admin_comment('#page'); ?>
		</div>
		<footer id="colophon" class="site-footer bg-dark text-white py-5">
			<div class="container py-5">
				<div class="row gy-4">
					<div class="col-md-3 col-12 text-center text-md-start">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
						} elseif ( file_exists( get_template_directory() . '/images/msstavby.gif' ) ) {
							echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-decoration-none d-inline-block bg-white">';
							echo '<img src="' . esc_url( get_template_directory_uri() . '/images/msstavby.gif' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="160" height="89" />';
							echo '</a>';
						} else {
							echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-decoration-none text-white">';
							echo '<h5 class="m-0">' . esc_html( bloginfo( 'name' ) ) . '</h5>';
							echo '</a>';
						}
						?>
						<a href="https://www.msstavby.cz/kontaktni-formular/" class="btn btn-primary mt-3">Přejít na kontakt &rarr;</a>
					</div>
					<div class="col-md-9 col-12">
						<div class="row justify-content-end">
							<div class="col-md-3 d-none d-md-block">
								<?php admin_comment('Empty column'); ?>
							</div>
							<div class="col-md-3">
								<nav id="footer-navigation" class="footer-nav">
                                    <h5 class="mb-3">Zjistěte více</h5>
									<?php
									wp_nav_menu( array(
										'theme_location' => 'footer',
										'menu_class'     => 'footer-menu list-unstyled',
										'fallback_cb'    => false,
									) );
									?>
								</nav>
							</div>
							<div class="col-md-3">
								<nav class="footer-nav">
									<h5 class="mb-3">Sociální sítě</h5>
									<?php
									wp_nav_menu( array(
										'theme_location' => 'social',
										'menu_class'     => 'footer-menu list-unstyled',
										'fallback_cb'    => false,
									) );
									?>
								</nav>
							</div>
						</div>
					</div>
				</div>
				<hr class="border-secondary my-4">
				<div class="d-flex justify-content-between align-items-center flex-wrap">
					<span class="copyright small">
						&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( bloginfo( 'name' ) ); ?>. All rights reserved
					</span>
					<div class="small">
						<a href="#" class="text-white text-decoration-none">Privacy Policy</a>
						<span class="mx-2">|</span>
						<a href="#" class="text-white text-decoration-none">Cookies</a>
					</div>
				</div>
			</div>
		</div>
		<?php admin_comment('#colophon'); ?>
	</footer>
<?php wp_footer(); ?>
</body>
</html>
