<?php
/**
 * The template for displaying image attachments
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

// Retrieve attachment metadata.
$metadata = wp_get_attachment_metadata();

get_header();
?>

<div id="main-content" class="main-content">
	<div class="content-wrapper">
	</div><!-- .content-wrapper -->
	<input type="hidden" id="current-post-page" value="2">
	<input type="hidden" id="current-page-url" value="">
	<div id="img-preloader"></div>
	<span class="posts-loading spinner"></span>
	<span id="no-more-posts"><?php _e('No more posts to show.', 'boron'); ?></span>
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
	<div class="single-post-inner"></div>
	<div class="single-post-side"></div>
	<?php if ( get_theme_mod('boron_comment_location', 'side') == 'side' ) { ?>
		<div class="single-post-side-comments"></div>
	<?php } ?>
</div>

<?php
get_footer();
