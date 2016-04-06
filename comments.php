<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */	
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-container">

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<?php
			printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'boron' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'boron' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'boron' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'boron' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

	<ol class="commentlist">
		<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 64,
			) );
		?>
	</ol><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'boron' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'boron' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'boron' ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'boron' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(
		array('comment_notes_after' => '',
				'logged_in_as' => '',
				'url' => '',
				'title_reply'      => __( 'Leave a reply', 'boron'),
				'comment_notes_before' => '<span class="comment-form-before">' . __('Your email address will not be published. Required fields are marked*', 'boron') . '</span>',
				'label_submit'    => __( 'Post Comment', 'boron'),
				'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __('Comment*', 'boron') . '">' . '</textarea></p>',
				'fields' => array(
					'author' =>
						'<div class="comment-form-top">
						<span class="comment-author"><input id="author" name="author" type="text" placeholder="' . __('Name*', 'boron') . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" /></span>',
					'email' =>
						'<span class="comment-email"><input id="email" name="email" type="text" placeholder="' . __('E-mail*', 'boron') . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" /></span>',
					'url' =>
						'<span class="comment-url"><input id="url" name="url" type="text" placeholder="' . __('Website', 'boron') . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></span></div>',
				)
			)
		); ?>

</div><!-- #comments -->