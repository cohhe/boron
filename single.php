<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

get_header();

global $boron_site_width;
?>

<div id="main-content" class="main-content row">
	<div class="content-wrapper">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */

				get_template_part( 'content', get_post_format() ? get_post_format() : get_post_type() );

			endwhile;
		?>
	</div><!-- .content-wrapper -->
</div><!-- #main-content -->
<?php
	$post_bg = get_theme_mod('boron_post_background');
	if ( $post_bg ) {
		$background = 'background: url(' . $post_bg . ');';
	} else {
		$background = 'background-color: #999;';
	}
?>
<div class="single-post-background" style="<?php echo $background; ?>"></div>
<div class="single-post-container">
	<div class="single-post-inner"><?php echo boron_get_single_post( get_the_ID() ); ?></div>
	<div class="single-post-side"><?php echo boron_get_single_post_side( get_the_ID() ); ?></div>
	<?php if ( get_theme_mod('boron_comment_location', 'side') == 'side' ) { ?>
		<div class="single-post-side-comments"></div>
	<?php } ?>
</div>

<?php
get_footer();