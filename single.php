<?php
/**
 * The template for displaying single posts
 *
 * @package Ostryweb
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container" id="main-content">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-detail' ); ?>>
			<?php admin_comment('Tags as chips at top (same styling as home page category filter)'); ?>
			<?php if ( has_tag() ) { ?>
				<div class="post-tags-chips mb-4">
					<?php
					$tags = get_the_tags();
					if ( $tags && ! is_wp_error( $tags ) ) {
						foreach ( $tags as $tag ) {
							echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="filter-chip">' . esc_html( $tag->name ) . '</a>';
						}
					}
					?>
				</div>
			<?php } ?>

			<?php admin_comment('Title'); ?>
			<header class="entry-header mb-0">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><?php admin_comment('.entry-header'); ?>

			<?php admin_comment('60px space'); ?>
		    <div class="spacer-60"></div>
		    <?php admin_comment('Excerpt'); ?>
		    <?php
			   if ( has_excerpt() ) {
				    echo '<div class="post-excerpt mb-0">' . wp_kses_post( get_the_excerpt() ) . '</div>';
		    }
		    ?>

		<?php admin_comment('60px space'); ?>
			<div class="spacer-60"></div>

		<?php admin_comment('Meta line: Author &#9679; Post date, and sharing buttons on the right'); ?>
			<div class="entry-meta d-flex justify-content-between align-items-center mb-4">
				<div class="post-meta-info">
					<span class="post-author">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
							<?php the_author(); ?>
						</a>
					</span>
				<span class="post-separator">&nbsp;&#9679;&nbsp;</span>
					<time class="post-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
					</time>

					<?php
					if ( has_category() ) {
						?>
						<span class="post-categories">
							<?php the_category( ', ' ); ?>
						</span>
						<?php
					}
					?>
				</div>

			<?php admin_comment('Sharing buttons (placeholder for plugin output)'); ?>
				<div class="post-sharing">
					<?php
					if ( function_exists( 'do_shortcode' ) ) {
						// Check common social sharing plugin shortcodes
						if ( has_shortcode( get_the_content(), 'addtoany' ) ) {
							echo do_shortcode( '[addtoany]' );
						} elseif ( has_shortcode( get_the_content(), 'heateor_sss_shortcode' ) ) {
							echo do_shortcode( '[heateor_sss_shortcode]' );
						}
					}
					?>
				</div>
			</div><?php admin_comment('.entry-meta'); ?>

			<?php admin_comment('Content'); ?>
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
		</div><?php admin_comment('.entry-content'); ?>

			<?php admin_comment('Sharing buttons again on the right (after content)'); ?>
			<div class="post-sharing mt-4">
				<?php
				if ( function_exists( 'do_shortcode' ) ) {
					if ( has_shortcode( get_the_content(), 'addtoany' ) ) {
						echo do_shortcode( '[addtoany]' );
					} elseif ( has_shortcode( get_the_content(), 'heateor_sss_shortcode' ) ) {
						echo do_shortcode( '[heateor_sss_shortcode]' );
					}
				}
				?>
			</div>

</article><?php admin_comment('#post-'.get_the_ID()); ?>

<?php admin_comment('Navigation (simple format like legacy version)'); ?>
	<nav class="post-navigation-simple">
		<div class="nav-previous"><?php previous_post_link( '&laquo; %link' ); ?></div>
		<div class="nav-next"><?php next_post_link( '%link &raquo;' ); ?></div>
	</nav>

	<?php
	// Comments
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	}
	?>
	</div><?php admin_comment('.container'); ?>
</main><?php admin_comment('#primary'); ?>
<?php
get_footer();
