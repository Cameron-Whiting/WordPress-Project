<?php
/**
 * bok-ecommerce functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bok-ecommerce
 */

if ( ! function_exists( 'bok_ecommerce_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bok_ecommerce_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on bok-ecommerce, use a find and replace
		 * to change 'bok_ecommerce' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'bok_ecommerce', get_template_directory() . '/languages' );

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

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'bok_ecommerce' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'bok_ecommerce_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'bok_ecommerce_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bok_ecommerce_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'bok_ecommerce_content_width', 640 );
}
add_action( 'after_setup_theme', 'bok_ecommerce_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bok_ecommerce_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'bok_ecommerce' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'bok_ecommerce' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'bok_ecommerce_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bok_ecommerce_scripts() {
	//Enqueue Google Fonts 
	wp_enqueue_style('gpfonts','https://fonts.googleapis.com/css?family=Lobster');
	wp_enqueue_style('gpfonts','https://fonts.googleapis.com/css?family=Roboto:400,700,900');
	wp_enqueue_style('gpfonts','https://fonts.googleapis.com/css?family=Montserrat:400,400i,700&display=swap');
	wp_enqueue_style( 'bok_ecommerce-style', get_stylesheet_uri() );

	wp_enqueue_script( 'bok_ecommerce-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'bok_ecommerce-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bok_ecommerce_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/*Function that will display a random category at bottom of homepage*/
function list_categories()
{
	$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$num = rand(1,11);//Random number with product categories as the range
	/*MySql query retrieving category details from database*/
	$query = "SELECT wp_terms.* ";
    $query .= "FROM wp_terms ";
    $query .= "LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id ";
    $query .= "WHERE wp_term_taxonomy.taxonomy = 'product_cat'";
    $result = mysqli_query($connection,$query);
    
    echo '<div class = "beer_categories">';
    $i = 0;//Counter
    /*Loop over result array*/
    while($row = mysqli_fetch_assoc($result))
    {
    	/*If counter equals the random number echo details to the screen*/
     	if($i == $num)
     	{
     		echo "<p>Check out our <b>{$row['name']}</b> beers ";
     		echo "<a href='https://dig31-brendanokeefe-19363873-a3-brokeefe89.c9users.io/?product_cat={$row['slug']}'>here</p>";
     	}
     	$i++;
    }
    echo "</div>";
    mysqli_free_result($result);
    mysqli_close($connection);
}

/*Change image size in single product page*/
add_filter( 'woocommerce_get_image_size_single', 'ci_theme_override_woocommerce_image_size_single' );
function ci_theme_override_woocommerce_image_size_single( $size ) {
    return array(
        'width'  => 400,
        'height' => 550,
        'crop'   => 1,
    );
}

/*Remove magnify glass icon in single product page*/
add_action( 'after_setup_theme', 'remove_zoom', 99 );
 
function remove_zoom() { 
	remove_theme_support( 'wc-product-gallery-zoom' );
}

/*Remove meta details from single product page*/
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/*Retrieve all products that are on sale*/
function sale_items(){
	
	$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);	
	
	/*Retrieve name and title of product on sale*/
	$query = "SELECT post_name, post_title ";
	$query .= "FROM wp_posts ";
	$query .= "LEFT JOIN wp_postmeta wp_postmeta1 ON wp_postmeta1.post_id = wp_posts.ID ";
	$query .= "AND wp_postmeta1.meta_key =  '_sale_price' ";
	$query .= "WHERE wp_postmeta1.meta_key =  '_sale_price' ";
	$query .= "AND wp_posts.post_status =  'publish' ";
	$query .= "ORDER BY wp_posts.post_title ASC";
	
	$result = mysqli_query($connection,$query);
	
	echo '<div class = "sale_item_list">';
	echo '<button class = "sale_button">SALE</button>';
	echo '<div class = "sale_content">';
	/*loop over result and use product name slug to use as link*/
	while($row = mysqli_fetch_assoc($result)){
		echo "<a href='https://dig31-brendanokeefe-19363873-a3-brokeefe89.c9users.io/?product={$row['post_name']}'>{$row['post_title']}</a>";
	}
	
	echo "</div>";
	echo "</div>";
    mysqli_free_result($result);
    mysqli_close($connection);
	
}
/*Remove product image from shopping cart*/
add_filter( 'woocommerce_cart_item_thumbnail', '__return_false' );

/*Remove coupon form from shopping cart and checkout*/
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

function hide_coupon() {
  $bool = true;
  if ( is_cart() ) {
    $bool = false;
  }
  return $bool;
}

add_filter( 'woocommerce_coupons_enabled', 'hide_coupon' );
?>