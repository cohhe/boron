<?php
/**
 * Boron 1.0 functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see boron_content_width()
 *
 * @since Boron 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * Boron 1.0 only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'boron_setup' ) ) :
	/**
	 * Boron 1.0 setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 * @since Boron 1.0
	 */
	function boron_setup() {
		/**
		 * Required: include TGM.
		 */
		require_once( get_template_directory() . '/functions/tgm-activation/class-tgm-plugin-activation.php' );

		/*
		 * Make Boron 1.0 available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Boron 1.0, use a find and
		 * replace to change 'boron' to the name of your theme in all
		 * template files.
		 */
		load_theme_textdomain( 'boron', get_template_directory() . '/languages' );

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( array( 'css/editor-style.css' ) );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 672, 372, true );
		add_image_size( 'boron-small-thumbnail', 70, 70, true );
		add_image_size( 'boron-full-width', 1170, 400, true );
		add_image_size( 'boron-thumbnail', 490, 318, true );
		add_image_size( 'boron-thumbnail-large', 650, 411, true );
		add_image_size( 'boron-medium-thumbnail', 350, 350, false );
		add_image_size( 'boron-related-thumbnail', 255, 170, true );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		) );

		// This theme allows users to set a custom background.
		add_theme_support( 'custom-background', apply_filters( 'boron_custom_background_args', array(
			'default-color' => 'fff',
		) ) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );

		add_theme_support( 'title-tag' );

		add_theme_support( 'custom-logo', array( 'width' => '128', 'height' => '128' ) );
	}
endif; // boron_setup
add_action( 'after_setup_theme', 'boron_setup' );

// Admin CSS
function boron_admin_css( $hook ) {
	wp_enqueue_style( 'boron-admin-css', get_template_directory_uri() . '/css/wp-admin.css' );
}
add_action('admin_enqueue_scripts','boron_admin_css');

/**
 * Returns list of tags for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_tag_list( $post_id, $return = false ) {
	$entry_utility = '';
	$posttags = get_the_tags( $post_id );
	if ( $posttags ) {
		$entry_utility .= '
		<div class="tag-link">
			<span class="icon-tags"></span>';
				foreach( $posttags as $tag ) {
					$entry_utility .= $tag->name . ' '; 
				}
			$entry_utility .= '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

/**
 * Returns list of tags with links for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_tag_link_list( $post_id, $return = false ) {
	$entry_utility = '';
	$posttags = get_the_tags( $post_id );
	if ( $posttags ) {
		$entry_utility .= '
		<div class="tag-link">
				<span class="tag-text">' . __('Tags', 'boron') . '</span>';
				foreach( $posttags as $tag ) {
					$entry_utility .= '<a href="' . get_tag_link($tag->term_id) . '" class="open-tag">' . $tag->name . '</a> '; 
				}
			$entry_utility .= '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

/**
 * Returns list of categories for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_category_list( $post_id, $return = false ) {
	$category_list = get_the_category_list( ', ', '', $post_id );
	$entry_utility = '';
	if ( $category_list ) {
		$entry_utility .= '
		<div class="category-link">
			<span class="entypo_icon icon-folder-open"></span>' . $category_list . '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

/**
 * Returns list of categories with links for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_category_link_list( $post_id, $return = false ) {
	$category_list = get_the_category_list( ', ', '', $post_id );
	$entry_utility = '';
	if ( $category_list ) {
		$entry_utility .= '
		<div class="category-link">
			<span class="category-text">' . __('Categories', 'boron') . '</span>' . $category_list . '
		</div>';
	}

	if ( $return ) {
		return $entry_utility;
	} else {
		echo $entry_utility;
	}
}

/**
 * Returnscomment count for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_comment_count( $post_id ) {
	$comments = wp_count_comments($post_id); 
	return $comments->approved;
}

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Boron 1.0
 *
 * @return void
 */
function boron_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'boron_content_width' );

/**
 * Register Lato Google font for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	$font_url = add_query_arg( 'family', urlencode( 'Open+Sans:400,100,300' ), "//fonts.googleapis.com/css" );

	return $font_url;
}

/**
 * Limits the post excerpt for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'boron_excerpt_length', 999 );

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Boron 1.0
 *
 * @return void
 */
function boron_scripts() {

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array() );

	// Add Google fonts
	wp_register_style('boron-googleFonts', '//fonts.googleapis.com/css?family=Proxima+Nova:300,400,600,700&subset=latin');
	wp_enqueue_style( 'boron-googleFonts');

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'boron-style', get_stylesheet_uri(), array( 'genericons' ) );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'boron-ie', get_template_directory_uri() . '/css/ie.css', array( 'boron-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'boron-ie', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'comment-reply' );

	wp_enqueue_script( 'boron-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20131209', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '20131209', true );

	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css', array() );

	wp_enqueue_script( 'jquery.isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'jquery-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '', true );
	

	wp_enqueue_script( 'jquery-ui-draggable' );

	wp_localize_script(
		'boron-script',
		'WP_API_Settings',
		array(
			'root'           => esc_url_raw( rest_url() ),
			'nonce'          => wp_create_nonce( 'wp_rest' ),
			'posts_per_page' => get_option('posts_per_page'),
			'post_comments'  => get_theme_mod('boron_comment_location', 'side'),
			'home_url'       => esc_url( home_url() ),
			'dates'          => boron_get_archive(),
			'post_tax'       => boron_get_post_tax(),
			'rest_api_status' => function_exists('register_api_field'),
			'read_more'      => __('Read more', 'boron')
		)
	);

	wp_add_inline_style( 'boron-style', boron_set_grid_size() );

	// Add html5
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5.js' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'boron_scripts' );

/**
 * Sets the blog grid size for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_set_grid_size() {
	$column_count = get_theme_mod( 'boron_grid_columns', '4' );

	$column_width = 100/(int)$column_count;

	return '.main-content article { width: ' . esc_attr($column_width) . '%; }';
}

/**
 * Registers the rest api for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
add_action( 'rest_api_init', 'boron_register_extra_filters' );
function boron_register_extra_filters() {
	if ( function_exists('register_api_field') ) {
		register_api_field( 'post',
	        'boron_extra',
	        array(
	            'get_callback'    => 'boron_get_extra_fields',
	            'update_callback' => null,
	            'schema'          => null,
	        )
	    );
	}
}

/**
 * Add extra return fields for rest api for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_extra_fields( $object, $field_name, $request ) {
	if ( isset($object['featured_media']) ) {
		$image_id = (int)$object['featured_media'];
		$img = wp_get_attachment_image_src( $object['featured_media'], 'boron-medium-thumbnail' );
		$image_src = $img['0'];
	} else {
		$image_src = null;
	}

	$extra = array();

	$extra['image_src'] = $image_src;
	$extra['tag_list'] = boron_tag_list( $object['id'], true );
	$extra['category_list'] = boron_category_list( $object['id'], true );
	$extra['date_ago'] = human_time_diff(get_the_time('U', $object['id']), current_time('timestamp')) .  ' '.__('ago', 'boron');
	$extra['comments'] = boron_comment_count( $object['id'] );
	$extra['post_template'] = boron_get_single_post( $object['id'] );
	$extra['post_side_template'] = boron_get_single_post_side( $object['id'] );
	$extra['post_classes'] = implode( ' ', get_post_class('', $object['id'] ) );

    return $extra;
}

/**
 * Returns an html of a single post for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_single_post( $post_id ) {
	$output = '';

	// Check if thumbnail exists
	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'boron-full-width' );
	if ( !empty($img) ) {
		$output .= '<div class="open-post-image"><img src="'.$img['0'].'" class="single-open-post-image" alt="Post with image"></div>';
	}

	$output .= '<h1>' . get_the_title( $post_id ) . '</h1>';

	$content_post = get_post( $post_id );
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);

	// Sets the post content
	if ( post_password_required( $post_id ) ) {
		$output .= get_the_password_form( $post_id );
	} else {
		$output .= $content;
		$output .= boron_get_single_post_pagination();
	}

	global $withcomments;
	$withcomments = 1;

	ob_start();
	comments_template();
	$comment_form = ob_get_clean();
	$comment_form = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $comment_form);

	$output .= '<div class="clearfix"></div><div class="comments-container">' . $comment_form . '</div>';

	return $output;
}

/**
 * Returns an html of a single post pagination for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_single_post_pagination( $post_id = '' ) {
	$output = '<nav class="nav-single blog">';
		$prev_post = get_previous_post();
		$next_post = get_next_post();

		if (!empty( $prev_post )) {
			$output .= '
			<div class="nav_button left">
				<h3 class="prev-post-text">'. __('Previous post', 'boron').'</h3>
				<div class="prev-post-link">
					<a href="'. get_permalink( $prev_post->ID ).'" class="prev_blog_post icon-left">'.get_the_title( $prev_post->ID ).'</a>
				</div>
			</div>';
		}

		if (!empty( $next_post )) {
			$output .= '
			<div class="nav_button right">
				<h3 class="next-post-text">'.__('Next post', 'boron').'</h3>
				<div class="next-post-link">
					<a href="'. get_permalink( $next_post->ID ).'" class="next_blog_post icon-right">'. get_the_title( $next_post->ID ).'</a>
				</div>
			</div>';
		}
		$output .= '
		<div class="clearfix"></div>
	</nav>';

	return $output;
}

/**
 * Returns an html of a single post "sidebar" for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_single_post_side( $post_id ) {
	$output = '';
	$date = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) .  ' '.__('ago', 'boron');
	$comments = boron_comment_count( $post_id );

	$output .= '<span class="single-open-posted">' . __('Posted', 'boron') . '</span>';
	$output .= '<span class="single-open-posted-date">' . $date . '</span>';

	$output .= boron_tag_link_list( $post_id, true );

	$output .= boron_category_link_list( $post_id, true );

	if ( comments_open( $post_id ) ) {
		$output .= '<div class="side-comments"><span class="single-open-comment">' . __('Comments', 'boron') . '</span>';
		$output .= '<span class="single-open-comment-count">' . $comments . '</span></div>';
	}

	if ( function_exists('boron_get_share_icons') ) {
		$output .= boron_get_share_icons( $post_id );
	}

	return $output;
}

/**
 * Returns archive date for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_archive() {
	$dates = array( 'year' => '', 'monthnum' => '', 'day' => '' );
	if ( !is_date() ) {
		return $dates;
	}

	$dates['year'] = get_query_var('year');
	$dates['monthnum'] = get_query_var('monthnum');
	$dates['day'] = get_query_var('day');

	return $dates;
}

/**
 * Returns taxonomies for a post for Boron 1.0.
 *
 * @since Boron 1.0
 *
 * @return string
 */
function boron_get_post_tax() {
	if ( isset( $GLOBALS['wp_query']->queried_object->term_id ) ) {
		$term_id = $GLOBALS['wp_query']->queried_object->term_id;
	} else {
		$term_id = '';
	}

	$tax_arrays = array(
		array(
			'field'=>'term_id',
			'taxonomy'=>'post_format',
			'terms'=> $term_id
		)
	);

	return serialize($tax_arrays);
}

// Admin Javascript
function boron_admin_scripts( $hook ) {
	wp_enqueue_script('master', get_template_directory_uri() . '/inc/js/admin-master.js', array('jquery'));
}
add_action( 'admin_enqueue_scripts', 'boron_admin_scripts' );

if ( ! function_exists( 'boron_the_attached_image' ) ) :
	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since Boron 1.0
	 *
	 * @return void
	 */
	function boron_the_attached_image() {
		$post                = get_post();
		/**
		 * Filter the default Boron 1.0 attachment size.
		 *
		 * @since Boron 1.0
		 *
		 * @param array $dimensions {
		 *     An array of height and width dimensions.
		 *
		 *     @type int $height Height of the image in pixels. Default 810.
		 *     @type int $width  Width of the image in pixels. Default 810.
		 * }
		 */
		$attachment_size     = apply_filters( 'boron_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			}

			// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
			}
		}

		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Boron 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function boron_body_classes( $classes ) {
	global $post;
	$boron_layout = '';

	$post_comments = get_theme_mod('boron_comment_location');
	if ( $post_comments == 'side' ) {
		$classes[] = 'post-side-comments';
	} elseif ( $post_comments == 'bottom' ) {
		$classes[] = 'post-bottom-comments';
	}

	if ( is_single() || is_page() ) {
		$classes[] = 'customize-support pull-content-to-side pull-content-to-side-ended';
	}

	return $classes;
}
add_filter( 'body_class', 'boron_body_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Boron 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function boron_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'boron' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'boron_wp_title', 10, 2 );

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

function boron_navigation_link( $text, $url ) {
	if ( !$text && !$url ) {
		return false;
	}

	return '<a href="' . $url . '" class="social-button" target="_blank">' . $text . '</a>';
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function boron_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     				=> 'Bootstrap 3 Shortcodes', // The plugin name
			'slug'     				=> 'bootstrap-3-shortcodes', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '3.3.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'WordPress REST API (Version 2)', // The plugin name
			'slug'     				=> 'rest-api', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '2.0-beta13', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Functionality for Boron theme', // The plugin name
			'slug'     				=> 'functionality-for-boron-theme', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'boron',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'boron' ),
			'menu_title'                       			=> __( 'Install Plugins', 'boron' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'boron' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'boron' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'boron' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'boron' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'boron' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'boron' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'boron' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'boron' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'boron' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'boron' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'boron_register_required_plugins' );

function boron_admin_rating_notice() {
	$user = wp_get_current_user();
	?>
	<div class="boron-rating-notice">
		<span class="boron-notice-left">
			<img src="<?php echo get_template_directory_uri(); ?>/images/logo-square.png" alt="">
		</span>
		<div class="boron-notice-center">
			<p>Hi there, <?php echo $user->data->display_name; ?>, we noticed that you've been using Boron for a while now.</p>
			<p>We spent many hours developing this free theme for you and we would appriciate if you supported us by rating it!</p>
		</div>
		<div class="boron-notice-right">
			<a href="https://wordpress.org/support/view/theme-reviews/boron?rate=5#postform" class="button button-primary button-large boron-rating-rate">Rate at WordPress</a>
			<a href="javascript:void(0)" class="button button-large preview boron-rating-dismiss">No, thanks</a>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php
}
if ( get_option('boron_rating_notice') && get_option('boron_rating_notice') != 'hide' && time() - get_option('boron_rating_notice') > 432000 ) {
	add_action( 'admin_notices', 'boron_admin_rating_notice' );
}

function boron_dismiss_rating_notice() {
	update_option('boron_rating_notice', 'hide');

	die(0);
}
add_action( 'wp_ajax_nopriv_boron_dismiss_notice', 'boron_dismiss_rating_notice' );
add_action( 'wp_ajax_boron_dismiss_notice', 'boron_dismiss_rating_notice' );

function boron_theme_activated() {
	if ( !get_option('boron_rating_notice') ) {
		update_option('boron_rating_notice', time());
	}
}
add_action('after_switch_theme', 'boron_theme_activated');