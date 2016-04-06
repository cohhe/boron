<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<?php
global $boron_site_width, $boron_layout_type;

$form_class    = '';
$class         = '';
$search_string = '';

?>
<body <?php body_class(); ?>>
<?php do_action('ase_theme_body_inside_top'); ?>
<div id="page" class="hfeed site">
	<div id="main" class="site-main container">
		<?php
			$navigation_bg = get_theme_mod('boron_navigation_background', get_template_directory_uri() . '/images/navigation-bg.png');
			
			if ( $navigation_bg ) {
				$navigation_style = 'background: url(' . esc_url($navigation_bg) . ');';
			} else {
				$navigation_style = 'background-color: rgba(0,0,0,0.5);';
			}

			$logo = get_custom_header();
			$logo = $logo->url;
			$title = get_theme_mod('boron_nav_title');
			$description = get_theme_mod('boron_nav_description');
		?>
		<?php if ( $logo ) { ?>
			<div class="site-small-logo">
				<img src="<?php echo $logo; ?>" alt="<?php _e('Site logo', 'boron'); ?>">
			</div>
		<?php } ?>
		<?php if ( get_option('show_on_front') == 'posts' ) { ?>
			<span class="single-post-close"><span class="close-icon"></span></span>
		<?php } else if ( get_queried_object_id() != get_option('page_on_front') ) { ?>
			<span class="single-post-close"><span class="close-icon"></span></span>
		<?php } ?>
		<div class="site-navigation" style="<?php echo $navigation_style; ?>">
			<div class="navigation-wrapper">
				<span class="main-search icon-search"></span>
				<?php if ( $logo ) { ?>
					<div class="site-logo">
						<a href="<?php echo home_url(); ?>"><img src="<?php echo $logo; ?>" alt="<?php _e('Site logo', 'boron'); ?>"></a>
					</div>
				<?php } ?>
				<?php if ( $title ) { ?>
					<h1 class="navigation-title"><?php echo esc_html($title); ?></h1>
				<?php } ?>
				<?php if ( $description ) { ?>
					<p class="navigation-description"><?php echo esc_html($description); ?></p>
				<?php } ?>
			</div>
			<div class="navigation-copyright">Theme by <a href="https://cohhe.com" target="_blank">Cohhe</a></div>
			<?php if ( function_exists('boron_navigation_social') ) { echo boron_navigation_social(); } ?>
		</div>
		<div class="main-search-form">
			<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Type and Press Enter to Search', 'placeholder', 'boron' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'boron' ) ?>" />
			</form>
		</div>