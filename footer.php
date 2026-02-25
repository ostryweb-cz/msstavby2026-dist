<?php
/**
 * The footer for our theme
 *
 * @package msstavby
 */

?>
		</div><!-- #page -->
		<footer id="colophon" class="site-footer bg-dark text-white py-5">
			<div class="container py-5">
				<div class="row gy-4">
					<div class="col-12 col-md-3">
						<h5 class="mb-3">Logo</h5>
						<p class="small">Lorem ipsum dolor sit amet consectetur.</p>
						<a href="#" class="btn btn-primary mt-3">Přejít na kontakt &rarr;</a>
					</div>
					<div class="col-12 col-md-3 col-lg-2">
						<h5 class="mb-3">Sociální sítě</h5>
						<ul class="list-unstyled">
							<li><a href="https://www.facebook.com/msstavby" class="text-white text-decoration-none">Facebook</a></li>
							<li><a href="#" class="text-white text-decoration-none">X (Twitter)</a></li>
							<li><a href="#" class="text-white text-decoration-none">Instagram</a></li>
							<li><a href="#" class="text-white text-decoration-none">YouTube</a></li>
						</ul>
					</div>
					<div class="col-12 col-md-3">
						<h5 class="mb-3">Zjistěte více</h5>
						<ul class="list-unstyled">
							<li><a href="#" class="text-white text-decoration-none">Projekty</a></li>
							<li><a href="#maps" class="text-white text-decoration-none">Mapa projektů</a></li>
							<li><a href="#" class="text-white text-decoration-none">Inzerce</a></li>
							<li><a href="#" class="text-white text-decoration-none">Kontakt</a></li>
						</ul>
					</div>
					<div class="col-12 col-lg-4">
						<nav id="footer-navigation" class="footer-nav">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-menu list-unstyled',
								'fallback_cb'    => false,
							) );
							?>
						</nav>
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
		</footer><!-- #colophon -->
<?php wp_footer(); ?>
</body>
</html>
