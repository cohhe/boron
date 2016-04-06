<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

get_header();

global $boron_site_width, $boron_layout_type;

?>

<div id="main-content" class="main-content ">
	<div class="content-wrapper">

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

				endwhile;
			?>

	</div><!-- .content-wrapper -->
</div><!-- #main-content -->
<?php
	$post_bg = get_theme_mod('boron_post_background');
	if ( $post_bg ) {
		$background = 'background: url(' . esc_url($post_bg) . ');';
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