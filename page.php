<?php
/**
 * The template for displaying all pages
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
			<?php admin_comment('Title'); ?>
			<header class="entry-header mb-0">
					<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><?php admin_comment('.entry-header'); ?>

			<?php admin_comment('60px space'); ?>
			<div class="spacer-60"></div>

			<?php admin_comment('Content'); ?>
				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current page. Only visible to screen readers */
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

			</article><?php admin_comment('#post-'.get_the_ID()); ?>
		<?php
		}
	?>
	</div><?php admin_comment('.container'); ?>
</main><?php admin_comment('#primary'); ?>
<?php
get_footer();