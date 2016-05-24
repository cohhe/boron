<?php
/**
 * Boron 1.0 Theme Customizer support
 *
 * @package WordPress
 * @subpackage Boron
 * @since Boron 1.0
 */

/**
 * Implement Theme Customizer additions and adjustments.
 *
 * @since Boron 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function boron_customize_register( $wp_customize ) {
	// Add custom description to Colors and Background sections.
	$wp_customize->get_section( 'colors' )->description           = __( 'Background may only be visible on wide screens.', 'boron' );
	$wp_customize->get_section( 'background_image' )->description = __( 'Background may only be visible on wide screens.', 'boron' );

	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Rename the label to "Site Title Color" because this only affects the site title in this theme.
	$wp_customize->get_control( 'header_textcolor' )->label = __( 'Site Title Color', 'boron' );

	// Rename the label to "Display Site Title & Tagline" in order to make this option extra clear.
	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', 'boron' );

	// Add General setting panel and configure settings inside it
	$wp_customize->add_panel( 'boron_general_panel', array(
		'priority'       => 250,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'General settings' , 'boron'),
		'description'    => __( 'You can configure your general theme settings here' , 'boron')
	) );

	// Add navigation setting panel and configure settings inside it
	$wp_customize->add_panel( 'boron_navigation_panel', array(
		'priority'       => 250,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Side navigation settings' , 'boron'),
		'description'    => __( 'You can configure your theme side navigation settings here.' , 'boron')
	) );

	// Scroll to top
	$wp_customize->add_section( 'boron_general_scrolltotop', array(
		'priority'       => 30,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Scroll to top' , 'boron'),
		'description'    => __( 'Do you want to enable "Scroll to Top" button?' , 'boron'),
		'panel'          => 'boron_general_panel'
	) );

	$wp_customize->add_setting( 'boron_scrolltotop', array( 'sanitize_callback' => 'boron_sanitize_checkbox' ) );

	$wp_customize->add_control(
		'boron_scrolltotop',
		array(
			'label'      => __( 'Scroll to top', 'boron' ),
			'section'    => 'boron_general_scrolltotop',
			'type'       => 'checkbox',
		)
	);

	// Post background
	$wp_customize->add_section( 'boron_post_bg', array(
		'priority'       => 50,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Post background' , 'boron'),
		'description'    => __( 'Do you want your own post background? You can change it here.' , 'boron'),
		'panel'          => 'boron_general_panel'
	) );

	$wp_customize->add_setting( 'boron_post_background', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'boron_post_background', array(
		'label'    => __( 'Post background', 'boron' ),
		'section'  => 'boron_post_bg',
		'settings' => 'boron_post_background',
	) ) );

	// Comment position
	$wp_customize->add_section( 'boron_post_comments', array(
		'priority'       => 60,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Post comments' , 'boron'),
		'description'    => __( 'Choose where to show post comments.' , 'boron'),
		'panel'          => 'boron_general_panel'
	) );

	$wp_customize->add_setting( 'boron_comment_location', array( 'default' => 'side', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'boron_comment_location',
		array(
			'label'      => __( 'Post comments position', 'boron' ),
			'section'    => 'boron_post_comments',
			'type'       => 'select',
			'choices'    => array( 'side' => 'Right side', 'bottom' => 'After post content' )
		)
	);

	// Grid size
	$wp_customize->add_section( 'boron_grid_size', array(
		'priority'       => 60,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Post grid size' , 'boron'),
		'description'    => __( 'Choose how many columns will there be at the post grid.' , 'boron'),
		'panel'          => 'boron_general_panel'
	) );

	$wp_customize->add_setting( 'boron_grid_columns', array( 'default' => '4', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'boron_grid_columns',
		array(
			'label'      => __( 'Post grid size', 'boron' ),
			'section'    => 'boron_grid_size',
			'type'       => 'select',
			'choices'    => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6'
				)
		)
	);

	// Background
	$wp_customize->add_section( 'boron_navigation_bg', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Navigation background' , 'boron'),
		'description'    => __( 'Do you want your own navigation background? You can change it here.' , 'boron'),
		'panel'          => 'boron_navigation_panel'
	) );

	$wp_customize->add_setting( 'boron_navigation_background', array( 'sanitize_callback' => 'esc_url_raw', 'default' => get_template_directory_uri() . '/images/navigation-bg.png' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'boron_navigation_background', array(
		'label'    => __( 'Navigation background', 'boron' ),
		'section'  => 'boron_navigation_bg',
		'settings' => 'boron_navigation_background',
	) ) );

	// Navigation title
	$wp_customize->add_section( 'boron_navigation_title', array(
		'priority'       => 30,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Navigation title' , 'boron'),
		'description'    => __( 'Title for the side navigation.' , 'boron'),
		'panel'          => 'boron_navigation_panel'
	) );

	$wp_customize->add_setting( 'boron_nav_title', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'boron_nav_title',
		array(
			'label'      => __( 'Navigation title', 'boron' ),
			'section'    => 'boron_navigation_title',
			'type'       => 'text',
		)
	);

	// Navigation description
	$wp_customize->add_section( 'boron_navigation_description', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Navigation description' , 'boron'),
		'description'    => __( 'Description for the side navigation.' , 'boron'),
		'panel'          => 'boron_navigation_panel'
	) );

	$wp_customize->add_setting( 'boron_nav_description', array( 'sanitize_callback' => 'boron_sanitize_textarea' ) );

	$wp_customize->add_control(
		'boron_nav_description',
		array(
			'label'      => __( 'Navigation description', 'boron' ),
			'section'    => 'boron_navigation_description',
			'type'       => 'textarea',
		)
	);

	// Social links
	$wp_customize->add_section( new boron_Customized_Section( $wp_customize, 'boron_social_links', array(
		'priority'       => 300,
		'capability'     => 'edit_theme_options'
		) )
	);

	$wp_customize->add_setting( 'boron_fake_field', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'boron_fake_field',
		array(
			'label'      => '',
			'section'    => 'boron_social_links',
			'type'       => 'text'
		)
	);
}
add_action( 'customize_register', 'boron_customize_register' );

add_action( 'customize_register', function( $wp_customize ) {
        /** @var WP_Customize_Manager $wp_customize */
        remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->nav_menus, 'enqueue_scripts' ) );
        remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
        remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->nav_menus, 'filter_dynamic_setting_args' ) );
        remove_filter( 'customize_dynamic_setting_class', array( $wp_customize->nav_menus, 'filter_dynamic_setting_class' ) );
        remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'print_templates' ) );
        remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'available_items_template' ) );
        remove_action( 'customize_preview_init', array( $wp_customize->nav_menus, 'customize_preview_init' ) );
}, 10 );

if ( class_exists( 'WP_Customize_Section' ) && !class_exists( 'boron_Customized_Section' ) ) {
	class boron_Customized_Section extends WP_Customize_Section {
		public function render() {
			$classes = 'accordion-section control-section control-section-' . $this->type;
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<style type="text/css">
					.cohhe-social-profiles {
						padding: 14px;
					}
					.cohhe-social-profiles li:last-child {
						display: none !important;
					}
					.cohhe-social-profiles li i {
						width: 20px;
						height: 20px;
						display: inline-block;
						background-size: cover !important;
						margin-right: 5px;
						float: left;
					}
					.cohhe-social-profiles li a {
						height: 20px;
						line-height: 20px;
					}
					#customize-theme-controls>ul>#accordion-section-boron_social_links {
						margin-top: 10px;
					}
					.cohhe-social-profiles li.documentation {
						text-align: right;
						margin-bottom: 60px;
					}
				</style>
				<ul class="cohhe-social-profiles">
					<li class="documentation"><a href="http://documentation.cohhe.com/boron" class="button button-primary button-hero" target="_blank"><?php _e( 'Documentation', 'boron' ); ?></a></li>
				</ul>
			</li>
			<?php
		}
	}
}

function boron_sanitize_checkbox( $input ) {
	// Boolean check 
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

function boron_sanitize_textarea( $text ) {
	return wp_kses_post( $text );
}

/**
 * Sanitize the Featured Content layout value.
 *
 * @since Boron 1.0
 *
 * @param string $layout Layout type.
 * @return string Filtered layout type (grid|slider).
 */
function boron_sanitize_layout( $layout ) {
	if ( ! in_array( $layout, array( 'slider' ) ) ) {
		$layout = 'slider';
	}

	return $layout;
}

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Boron 1.0
 */
function boron_customize_preview_js() {
	wp_enqueue_script( 'boron_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20131205', true );
}
add_action( 'customize_preview_init', 'boron_customize_preview_js' );

/**
 * Add contextual help to the Themes and Post edit screens.
 *
 * @since Boron 1.0
 *
 * @return void
 */
function boron_contextual_help() {
	if ( 'admin_head-edit.php' === current_filter() && 'post' !== $GLOBALS['typenow'] ) {
		return;
	}

	get_current_screen()->add_help_tab( array(
		'id'      => 'boron',
		'title'   => __( 'Boron 1.0', 'boron' ),
		'content' =>
			'<ul>' .
				'<li>' . sprintf( __( 'The home page features your choice of up to 6 posts prominently displayed in a grid or slider, controlled by the <a href="%1$s">featured</a> tag; you can change the tag and layout in <a href="%2$s">Appearance &rarr; Customize</a>. If no posts match the tag, <a href="%3$s">sticky posts</a> will be displayed instead.', 'boron' ), admin_url( '/edit.php?tag=featured' ), admin_url( 'customize.php' ), admin_url( '/edit.php?show_sticky=1' ) ) . '</li>' .
				'<li>' . sprintf( __( 'Enhance your site design by using <a href="%s">Featured Images</a> for posts you&rsquo;d like to stand out (also known as post thumbnails). This allows you to associate an image with your post without inserting it. Boron 1.0 uses featured images for posts and pages&mdash;above the title&mdash;and in the Featured Content area on the home page.', 'boron' ), 'http://codex.wordpress.org/Post_Thumbnails#Setting_a_Post_Thumbnail' ) . '</li>' .
				'<li>' . sprintf( __( 'For an in-depth tutorial, and more tips and tricks, visit the <a href="%s">Boron 1.0 documentation</a>.', 'boron' ), 'http://documentation.cohhe.com/boron' ) . '</li>' .
			'</ul>',
	) );
}
add_action( 'admin_head-themes.php', 'boron_contextual_help' );
add_action( 'admin_head-edit.php',   'boron_contextual_help' );
