<?php
/**
 * The template for displaying single posts
 *
 * @package Ostryweb
 */

get_header();
?>
	<main id="primary" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-detail' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<span class="post-author">
							<?php esc_html_e( 'By ', 'ostryweb' ); ?>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
								<?php the_author(); ?>
							</a>
						</span>

						<time class="post-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php
							esc_html_e( 'Posted on ', 'ostryweb' );
							echo esc_html( get_the_date() );
							?>
						</time>

						<?php
						if ( has_category() ) {
							echo '<span class="post-categories">';
							esc_html_e( 'in ', 'ostryweb' );
							the_category( ', ' );
							echo '</span>';
						}
						?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->

				<?php
				if ( has_post_thumbnail() ) {
					echo '<div class="entry-thumbnail">';
					the_post_thumbnail( 'full' );
					echo '</div>';
				}
				?>

				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ostryweb' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ostryweb' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

				<?php if ( has_tag() ) { ?>
					<footer class="entry-footer">
						<span class="post-tags">
							<?php esc_html_e( 'Tags: ', 'ostryweb' ); ?>
							<?php the_tags( '', ', ', '' ); ?>
						</span>
					</footer><!-- .entry-footer -->
				<?php } ?>
			</article><!-- #post-* -->

			<?php
			// Navigation
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'ostryweb' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'ostryweb' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// Comments
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

		endwhile;
		?>
	</main><!-- #primary -->
<?php
get_footer();
