<?php
get_header();
?>
<div class="home fix">
	<div class="main-single">
		<div class="fix" id="thisIsTop">
			<div class="navigation">
				<div class="alignleft"><?php previous_post_link( '&laquo; %link' ); ?></div>
				<div class="alignright"><?php next_post_link( '%link &raquo;' ); ?></div>
			</div>
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					?>
					<div class="post single fix" id="post-<?php the_ID(); ?>">
						<h1>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php the_title(); ?>
							</a>
						</h1>
						<div class="postMeta">
							<span class="date">
								<?php the_author(); ?> |
								<span title="<?php the_time( 'j.n.Y G:i' ); ?>">
									<?php the_time( 'j.n.Y' ); ?>
								</span> |
								<?php the_category( ', ' ); ?> |
								<?php the_tags( '#', ', #', '' ); ?> |
								<span class="comments">
									<a href="#comments"><?php comments_number( __( 'No Comments' ), __( '1 Comment' ), __( '% Comments' ) ); ?></a>
								</span>
								<?php edit_post_link( '[E]', '', '' ); ?>
							</span>
						</div>

						<div class="entry">
							<?php
							if ( $post->post_excerpt ) {
								the_excerpt();
							}
							the_content();
							?>
							<br />
						</div>
					</div>
				<?php }
			}
			?>

		</div>
		<div class="sidebarwrapper">
			<?php include( TEMPLATEPATH . '/right.php' ); ?>
		</div>
	</div>
</div>
<?php
get_footer();
