<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Boron 1.0
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

get_header();

global $boron_site_width;
?>
<div id="main-content" class="main-content">
	<div class="content-wrapper">

			<?php if ( have_posts() ) :

					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
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