<?php
/**
 * The template for displaying posts in the Image post format
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

global $boron_article_width;
if ( !is_single() ) {
	$header_class = 'simple';
} else {
	$header_class = '';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($boron_article_width); ?>>
	<header class="entry-header <?php echo $header_class; ?>">
		<?php
			if ( !is_single() && ( is_home() || is_archive() || is_search() ) ) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'boron-medium-thumbnail' );
				echo '<div class="single-image-container">';
				if ( !empty($img) ) {
					echo '<img src="'.$img['0'].'" class="single-post-image" alt="Post with image">';
				} else {
					echo '<span class="post-no-image"></span>';
				}
				echo '<div class="single-post-meta">';
						boron_tag_list( get_the_ID() );
						echo '<span class="single-post-date icon-clock">'.human_time_diff(get_the_time('U',get_the_ID()),current_time('timestamp')) .  ' '.__('ago', 'boron').'</span>';
						if ( comments_open( get_the_ID() ) ) {
							echo '<span class="single-post-like icon-chat">' . boron_comment_count( get_the_ID() ) . '</span>';
						}
				echo '</div>';
				echo '</div>';
				echo '<input type="hidden" class="single-post-url" value="' . get_the_permalink() . '">';
				echo '</header><!-- .entry-header -->';
				echo '<script type="text/template" class="single-post-template">';
				echo boron_get_single_post( get_the_ID() );
				echo '</script>';
				echo '<script type="text/template" class="single-post-side-template">';
				echo boron_get_single_post_side( get_the_ID() );
				echo '</script>';
			}
		?>
</article>