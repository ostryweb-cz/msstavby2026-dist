<?php
/**
 * The home/blog template file
 *
 * @package msstavby
 */

get_header();
?>
	<main id="primary" class="site-main">
		<div class="container" id="main-content">
			<h1 class="posts-title my-5"><?php bloginfo( 'name' ); ?></h1>

			<?php
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$args = array(
				'posts_per_page' => 6,
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'paged'          => $paged
			);
			$posts = new WP_Query( $args );

			if ( $posts->have_posts() ) {
			?>
			<div class="row" id="projects">
				<?php
				while ( $posts->have_posts() ) {
					$posts->the_post();
					?>
					<div class="col-12 col-md-4 mb-4">
						<div class="card h-100">
							<?php if ( has_post_thumbnail() ) { ?>
							<div class="card-img-top position-relative" style="height: 240px; overflow: hidden;">
								<?php the_post_thumbnail( 'medium', array( 'class' => 'w-100 h-100 object-fit-cover' ) ); ?>
							</div>
							<?php } ?>
							<div class="card-body d-flex flex-column">
								<small class="text-muted mb-2">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
									</time>
								</small>
								<h5 class="card-title">
									<a href="<?php the_permalink(); ?>" class="text-decoration-none">
										<?php the_title(); ?>
									</a>
								</h5>
								<p class="card-text flex-grow-1">
									<?php the_excerpt(); ?>
								</p>
								<div class="d-flex justify-content-between align-items-center mt-auto">
									<a href="<?php the_permalink(); ?>" class="btn btn-primary">
										Číst dále &rarr;
									</a>
									<?php if ( comments_open() ) { ?>
									<span class="badge bg-secondary">
										<i class="bi bi-chat-dots"></i> <?php echo get_comments_number() ?>
									</span>
									<?php } ?>
								</div>
							</div>
						</div>
						</div>
					<?php
				}
				?>
			</div>
			
			<?php
				// Pagination
				$big = 999999999;
				$pagination = paginate_links( array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  => '?paged=%#%',
					'current' => max( 1, $paged ),
					'total'   => $posts->max_num_pages,
					'type'    => 'list',
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
				) );
				wp_reset_postdata();
				if ( $pagination ) {
					echo '<div class="row mt-4">';
					echo '<div class="col-12">';
					echo $pagination;
					echo '</div>';
					echo '</div>';
				}
				}
				?>
			
			<?php
			// Latest Comments Section
			$latest_comments = get_comments( array(
				'number' => 6,
				'status' => 'approve',
				'type' => 'comment',
			) );
			
			if ( $latest_comments ) {
			?>
			<section class="mt-5 pt-5">
				<h2 class "comments-title mb-4">Poslední komentáře</h2>
				<div class="row">
				<?php
					foreach ( $latest_comments as $comment ) {
						$post = get_post( $comment->comment_post_ID );
						$comment_comment = $comment->comment_content;
						$comment_excerpt = wp_trim_words( $comment_comment, 50, '...' );
						
						$comment_time = get_comment_date('d.m.Y', $comment->comment_ID ) . ' ' . get_comment_time('H:i', $comment->comment_ID);
					?>
					<div class="col-12 col-md-4 mb-3">
						<div class="card h-100">
							<div class="card-body">
								<div class="d-flex justify-content-between mb-2">
									<strong class="text-primary">
										<?php echo esc_html( $comment->comment_author ); ?>
									</strong>
									<span class="text-muted">
										<?php echo esc_html( get_comment_date('d.m.Y H:i', $comment->comment_ID) ); ?>
									</span>
								</div>
								<p class="card-text mb-2">
									<?php echo esc_html( $comment_excerpt ); ?>
								</p>
								<small class="text-muted">
									<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="text-decoration-none text-muted">
											<?php echo esc_html( get_the_title( $post ) ); ?>
										</a>
									</small>
							</div>
						</div>
					</div>
					<?php
					}
				?>
				</div>
			</section>
			<?php
			}
			?>
			
			<!-- Map Section -->
			<section class="my-5 py-5">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h2 class="m-0">Mapa projektů</h2>
						<a href="#projects" class="btn btn-outline-primary">Zobrazit projekty</a>
					</div>
					<div class="row">
						<div class="col-md-9">
							<div class="bg-light" style="height: 428px; display: flex; align-items: center; justify-content: center;">
								<span class="text-muted">Map visualization placeholder</span>
							</div>
						</div>
					<div class="col-md-3">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-3">Filtrujte podle lokality</h5>
									<form id="location-filter">
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-all" value="all" checked>
											<label class="form-check-label" for="filter-all">
												Všechny
											</label>
										</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-ostrava" value="ostrava">
											<label class="form-check-label" for="filter-ostrava">
												Ostrava
											</label>
										</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-opava" value="opava">
											<label class="form-check-label" for="filter-opava">
												Opava
											</label>
										</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-havířov" value="havířov">
											<label class="form-check-label" for="filter-havířov">
												Havířov
											</label>
									</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-frýdek" value="frýdek">
											<label class="form-check-label" for="filter-frýdek">
												Frýdek-místek
											</label>
									</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-ostatní" value="ostatní">
											<label class="form-check-label" for="filter-ostatní">
												ostatní
											</label>
									</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-multiple" value="multiple">
											<label class="form-check-label" for="filter-multiple">
												více článků
											</label>
									</div>
										<div class="form-check mb-2">
											<input class="form-check-input" type="checkbox" id="filter-locations" value="locations">
											<label class="form-check-label" for="filter-locations">
												více lokací v jednom článků
											</label>
									</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			<!-- Partners Section -->
			<section class="py-5">
				<div class="container">
					<h2 class="mb-4">Naši partneři</h2>
					<p class="mb-4">Máte co nabídnout? Rádi vás zařadíme mezi partnery webu!</p>
					<div class="d-flex flex-wrap gap-5 text-center">
						<div><strong>Ostravské sochy</strong></div>
						<div><strong>Kabinet architektury</strong></div>
						<div><strong>Infocity</strong></div>
						<div><strong>Petr Adamec</strong></div>
						<div><strong>Olstavby</strong></div>
					</div>
				</div>
			</section>
		</div><!-- .container -->
	</main><!-- #primary -->
<?php
get_footer();
