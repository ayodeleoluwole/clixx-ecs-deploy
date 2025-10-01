<?php
/**
 * open shop functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Open Shop
 * @since 1.0.0
 */
/**
 * Theme functions and definitions
 */
if ( ! function_exists( 'open_shop_setup' ) ) :
define( 'OPEN_SHOP_THEME_VERSION','1.6.9');
define( 'OPEN_SHOP_THEME_DIR', get_template_directory() . '/' );
define( 'OPEN_SHOP_THEME_URI', get_template_directory_uri() . '/' );
define( 'OPEN_SHOP_THEME_SETTINGS', 'open-shop-settings' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_open_shop_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function open_shop_setup(){
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'open-shop', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'woocommerce' );
	
		// Add support for Block Styles.
        add_theme_support( 'wp-block-styles' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );

        // Enqueue editor styles.
        add_editor_style( 'style-editor.css' );

        add_editor_style( 'editor.css' );
        // Add support for responsive embedded content.
        add_theme_support( 'responsive-embeds' );

        add_theme_support( 'custom-spacing' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		//Remove theme support for widget block editor 
		/**
		 * Add support for core custom logo.
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		// Add support for Custom Header.
		add_theme_support( 'custom-header', 

			apply_filters( 'open_shop_custom_header_args', array(
				'default-image' => '',
				'flex-height'   => true,
				'header-text'   => false,
				'video'          => false,
		) 


		) );
		
		// Add support for Custom Background.
		if(get_theme_mod('open_shop_color_scheme')=='opn-dark'){
        $args = array(
	    'default-color' => '2f2f2f',
        );
        }else{
        $args = array(
	    'default-color' => 'f1f1f1',
        );	
        }
        add_theme_support( 'custom-background',$args );
        
        $GLOBALS['content_width'] = apply_filters( 'open_shop_content_width', 640 );
        add_theme_support( 'woocommerce', array(
                                                 'thumbnail_image_width' => 320,
                                             ) );


        // Recommend plugins
        add_theme_support( 'recommend-plugins', array(

            'hunk-companion' => array(
                'name' => esc_html__( 'Hunk Companion (Highly Recommended)', 'open-shop' ),
                'img' => 'icon-128x128.png',
                'active_filename' => 'hunk-companion/hunk-companion.php',
            ),

			'vayu-blocks' => array(
				'name' => esc_html__( 'Vayu blocks For Gutenberg', 'open-shop' ),
				'img' => 'icon-128x128.png',
				'active_filename' => 'vayu-blocks/vayu-blocks.php',
				),
            'th-advance-product-search' => array(
            'name' => esc_html__( 'TH Advance Product Search', 'open-shop' ),
            'img' => 'icon-128x128.gif',
            'active_filename' => 'th-advance-product-search/th-advance-product-search.php',
            ),

            'th-all-in-one-woo-cart' => array(
                 'name' => esc_html__( 'Th All In One Woo Cart', 'open-shop' ),
                  'img' => 'icon-128x128.gif',
                 'active_filename' => 'th-all-in-one-woo-cart/th-all-in-one-woo-cart.php',
             ),
            
            'th-product-compare' => array(
                 'name' => esc_html__( 'Th Product Compare', 'open-shop' ),
                  'img' => 'icon-128x128.gif',
                 'active_filename' => 'th-product-compare/th-product-compare.php',
             ),

            'th-wishlist' => array(
				'name' => esc_html__( 'TH Wishlist for WooCommerce', 'open-shop' ),
				'img' => 'icon-128x128.gif',
				'active_filename' => 'th-wishlist/th-wishlist.php',
				),

			 'lead-form-builder' => array(
                'name' => esc_html__( 'Lead Form Builder', 'open-shop' ),
                 'img' => 'icon-128x128.png',
                'active_filename' => 'lead-form-builder/lead-form-builder.php',
            ),

            'th-variation-swatches' => array(
                'name' => esc_html__( 'TH Variation Swatches', 'open-shop' ),
                 'img' => 'icon-128x128.gif',
                'active_filename' => 'th-variation-swatches/th-variation-swatches.php',
            ),
           
			'wp-popup-builder' => array(
					'name' => esc_html__( 'WP Popup Builder – Popup Forms & Newsletter', 'open-shop' ),
					 'img' => 'icon-128x128.png',
					'active_filename' => 'wp-popup-builder/wp-popup-builder.php',
				), 
        
        ) );

        // Import Data Content plugins
        add_theme_support( 'import-demo-content', array(

             'hunk-companion' => array(
                'name' => esc_html__( 'Hunk Companion', 'open-shop' ),
                'img' => 'icon-128x128.png',
                'active_filename' => 'hunk-companion/hunk-companion.php',
            )
        ));



        // Useful plugins
        add_theme_support( 'useful-plugins', array(
             'themehunk-megamenu-plus' => array(
                'name' => esc_html__( 'Megamenu plugin from Themehunk.', 'open-shop' ),
                'active_filename' => 'themehunk-megamenu-plus/themehunk-megamenu.php',
            ),
        ) );

        

	}
endif;
add_action( 'after_setup_theme', 'open_shop_setup' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 */
/**
 * Register widget area.
 */
function open_shop_widgets_init(){
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'open-shop' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your primary sidebar.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="open-shop-widget-content">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Above Header First Widget', 'open-shop' ),
		'id'            => 'top-header-widget-col1',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Above Header Second Widget', 'open-shop' ),
		'id'            => 'top-header-widget-col2',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Above Header Third Widget', 'open-shop' ),
		'id'            => 'top-header-widget-col3',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar(array(
		'name'          => esc_html__( 'Main Header Widget', 'open-shop' ),
		'id'            => 'main-header-widget',
		'description'   => esc_html__( 'Add widgets here to appear in main header.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
    register_sidebar(array(
		'name'          => esc_html__( 'Footer Top First Widget', 'open-shop' ),
		'id'            => 'footer-top-first',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Top Second Widget', 'open-shop' ),
		'id'            => 'footer-top-second',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Top Third Widget', 'open-shop' ),
		'id'            => 'footer-top-third',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Below First Widget', 'open-shop' ),
		'id'            => 'footer-below-first',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Below Second Widget', 'open-shop' ),
		'id'            => 'footer-below-second',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Below Third Widget', 'open-shop' ),
		'id'            => 'footer-below-third',
		'description'   => esc_html__( 'Add widgets here to appear in top footer.', 'open-shop' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	for ( $i = 1; $i <= 4; $i++ ){
		register_sidebar( array(
			'name'          => sprintf( esc_html__( 'Footer Widget Area %d', 'open-shop' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}
	
}
add_action( 'widgets_init', 'open_shop_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function open_shop_scripts(){

	// enqueue css
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'font-awesome', OPEN_SHOP_THEME_URI . '/third-party/fonts/font-awesome/css/font-awesome.css', '', OPEN_SHOP_THEME_VERSION );

	wp_enqueue_style( 'th-icon', OPEN_SHOP_THEME_URI . '/third-party/fonts/th-icon/style.css','',OPEN_SHOP_THEME_VERSION);

	wp_enqueue_style( 'animate', OPEN_SHOP_THEME_URI . '/css/animate.css','',OPEN_SHOP_THEME_VERSION);
	wp_enqueue_style( 'open-shop-menu', OPEN_SHOP_THEME_URI . '/css/open-shop-menu.css','',OPEN_SHOP_THEME_VERSION);
	
	if((bool)get_theme_mod('open_shop_rtl')==true || in_array('rtl', get_body_class())){
	wp_enqueue_style( 'open-shop-rtl-style', OPEN_SHOP_THEME_URI . 'css/rtl.css','',OPEN_SHOP_THEME_VERSION);	
	}else{
	wp_enqueue_style( 'open-shop-main-style', OPEN_SHOP_THEME_URI . 'css/style.css','',OPEN_SHOP_THEME_VERSION);	
	}
	wp_enqueue_style( 'open-shop-style', get_stylesheet_uri(), array(), OPEN_SHOP_THEME_VERSION );
	wp_add_inline_style('open-shop-style', open_shop_custom_style());
	wp_add_inline_style('open-shop-style', '@font-face{font-family:"th-icon";src:url("' . esc_url(get_template_directory_uri() . '/third-party/fonts/th-icon/fonts/th-icon.ttf?k3xn19') . '") format("truetype");font-weight:normal;font-style:normal;font-display:block;}');
    //enqueue js
    //wp_enqueue_script("jquery-effects-core",array( 'jquery' ));
    wp_enqueue_script( 'jquery-ui-autocomplete',array( 'jquery' ),'',true );
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('open-shop-pro-menu-js', OPEN_SHOP_THEME_URI .'/js/open-shop-menu.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script('open-shop-accordian-menu-js', OPEN_SHOP_THEME_URI .'/js/open-shop-accordian-menu.js', array( 'jquery' ), OPEN_SHOP_THEME_VERSION , true );
    wp_enqueue_script('open-shop-custom-js', OPEN_SHOP_THEME_URI .'/js/open-shop-custom.js', array( 'jquery' ), OPEN_SHOP_THEME_VERSION , true );
     $openshoplocalize = array(
				'open_shop_top_slider_optn' => (bool) get_theme_mod('open_shop_top_slider_optn',false),
				'open_shop_move_to_top_optn' => (bool) get_theme_mod('open_shop_move_to_top',false),
			);
    wp_localize_script( 'open-shop-custom-js', 'open_shop',  $openshoplocalize);
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'open_shop_scripts' );


if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/********************************************************/
// Adding Dashicons in WordPress Front-end
/********************************************************/
add_action( 'wp_enqueue_scripts', 'open_shop_load_dashicons_front_end' );
function open_shop_load_dashicons_front_end(){
  wp_enqueue_style( 'dashicons' );
}
/**
 * Load init.
 */
require_once trailingslashit(OPEN_SHOP_THEME_DIR).'inc/init.php';
//custom function conditional check for blog page
function open_shop_is_blog(){
    return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
}