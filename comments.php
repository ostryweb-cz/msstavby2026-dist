<?php
/**
 * The template for displaying comments
 *
 * @package Ostryweb
 */

if ( post_password_required() ) {
	return;
}

$comments_number = get_comments_number();
?>
<section id="comments" class="comments-section">
  <h3 class="comments-title">
    Komentáře
  </h3>
	<?php
	comment_form(
		array(
			'title_reply'          => '',
			'title_reply_before'   => '',
			'title_reply_after'    => '',
			'class_form'           => 'comment-form',
			'comment_field'        => '<div class="form-group"><textarea id="comment" name="comment" class="form-control" rows="5" required placeholder="' . esc_attr__( 'Zde napište komentář...', 'ostryweb' ) . '"></textarea></div>',
			'fields'               => array(
				'author' => '<div class="comment-form-fields"><div class="form-group"><label for="author" class="form-label">' . esc_html__( 'Jméno', 'ostryweb' ) . '</label><input id="author" name="author" type="text" class="form-control" required placeholder="' . esc_attr__( 'Zde napište jméno', 'ostryweb' ) . '" /></div>',
				'email'  => '<div class="form-group"><label for="email" class="form-label">' . esc_html__( 'E-mail', 'ostryweb' ) . '</label><input id="email" name="email" type="email" class="form-control" required placeholder="' . esc_attr__( 'Zde napište e-mail', 'ostryweb' ) . '" /></div></div>',
			),
			'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary">%4$s</button>',
			'submit_field'         => '<div class="form-submit text-end">%1$s</div>',
			//'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'ostryweb' ), wp_login_url( get_permalink() ) ) . '</p>',
			//'comment_notes_before' => '<p class="comment-notes">' . __( 'Pro zverjenění vašeho komentáře vyplňte následující políčka', 'ostryweb' ) . '</p>',
			//'comment_notes_after'  => '<p class="comment-notes-after">' . __( 'Tato stránka používá Akismet k omezení spamu. ', 'ostryweb' ) . ' <a href="https://akismet.com/privacy/" target="_blank">' . __( 'Podívejte se, jak vaše data z komentářů zpracováváme.', 'ostryweb' ) . '</a></p><p class="comment-disclaimer">' . __( 'Příspěvky vyjadřují názory čtenářů. Server neodpovídá za jejich obsah a nenese právní důsledky spojené s jejich zveřejněním. Vyhrazujeme si právo odstraňovat nepřijatelné příspěvky.', 'ostryweb' ) . '</p>',
		)
	);
	?>

	<?php if ( have_comments() ) : ?>
		<div class="comment-list-container">
			<ul class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ul',
					'short_ping' => true,
					'callback'   => 'msstavby_comment_callback',
				)
			);
			?>
			</ul>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="navigation comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'ostryweb' ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'ostryweb' ); ?></h2>
				<div class="pagination">
					<?php
					paginate_comments_links(
						array(
							'prev_text' => '<i class="fas fa-chevron-left"></i>',
							'next_text' => '<i class="fas fa-chevron-right"></i>',
							'type'      => 'list',
						)
					);
					?>
				</div>
			</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, leave a note.
	if ( ! comments_open() && $comments_number && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Komentáře uzavřeny', 'ostryweb' ); ?></p>
	<?php endif; ?>
</section>
