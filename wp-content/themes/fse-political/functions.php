<?php
/**
 * FSE Political functions and definitions
 *
 * @package FSE Political
 * @since 1.0
 */

if ( ! function_exists( 'fse_political_support' ) ) :
	function fse_political_support() {
		
		load_theme_textdomain( 'fse-political', get_template_directory() . '/languages' );

		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'custom-background', apply_filters( 'fse_political_custom_background', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
		
		add_theme_support( 'wp-block-styles' );

		add_editor_style( 'style.css' );

		define('FSE_POLITICAL_BUY_NOW',__('https://www.themescarts.com/products/political-campaign-wordpress-theme/','fse-political'));
		define('FSE_POLITICAL_FOOTER_BUY_NOW',__('https://www.themescarts.com/products/fse-political/','fse-political'));
		define('FSE_POLITICAL_BUNDLE_LINK',__('https://www.themescarts.com/products/wordpress-theme-bundle/','fse-political'));

	}
endif;
add_action( 'after_setup_theme', 'fse_political_support' );

/*-------------------------------------------------------------
 Enqueue Styles
--------------------------------------------------------------*/

if ( ! function_exists( 'fse_political_styles' ) ) :
	function fse_political_styles() {
		// Register theme stylesheet.
		wp_enqueue_style('fse-political-style', get_stylesheet_uri(), array(), wp_get_theme()->get('version') );
		wp_enqueue_style('owl-carousel-css', get_template_directory_uri(). '/assets/css/owl.carousel.css');
		wp_enqueue_style('fse-political-style-blocks', get_template_directory_uri(). '/assets/css/blocks.css');
		wp_enqueue_style('fse-political-style-responsive', get_template_directory_uri(). '/assets/css/responsive.css');
		wp_style_add_data( 'fse-political-basic-style', 'rtl', 'replace' );

		wp_enqueue_script( 'owl-carousel-js', get_theme_file_uri( '/assets/js/owl.carousel.js' ), array( 'jquery' ), true );
		wp_enqueue_script( 'wow-js', get_theme_file_uri( '/assets/js/wow.js' ), array( 'jquery' ), true );
		wp_enqueue_script( 'fse-political-custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), true );
		
		wp_enqueue_style( 'animate-css', get_template_directory_uri().'/assets/css/animate.css' );

		wp_enqueue_style( 'dashicons' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'fse_political_styles' );

function fse_political_enqueue_admin_script($hook) {
    // Enqueue admin JS for notices
    wp_enqueue_script('fse-political-welcome-notice', get_template_directory_uri() . '/inc/fse-political-theme-info-page/js/fse-political-welcome-notice.js', array('jquery'), '', true);
    
    // Localize script to pass data to JavaScript
    wp_localize_script('fse-political-welcome-notice', 'fse_political_localize', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fse_political_welcome_nonce'),
        'dismiss_nonce' => wp_create_nonce('fse_political_welcome_nonce'), // Nonce for dismissal
        'redirect_url' => admin_url('themes.php?page=fse-political-theme-info-page')
    ));
}
add_action('admin_enqueue_scripts', 'fse_political_enqueue_admin_script');

if (!function_exists('fse_political_enable_plugin_autoupdate')) {

    add_filter('auto_update_plugin', function ($update, $item) {
        if ($item->slug === 'siteready-coming-soon-under-construction') {
            return true;
        }
        return $update;
    }, 10, 2);

}

function fse_political_plugin_update_available($slug, $file) {
    $updates = get_site_transient('update_plugins');

    if (!isset($updates->response[$slug . '/' . $file])) {
        return false; // No update available
    }

    return $updates->response[$slug . '/' . $file];
}

require get_template_directory() .'/inc/TGM/tgm.php';

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

require_once get_theme_file_path( 'inc/fse-political-theme-info-page/templates/class-theme-notice.php' );
require_once get_theme_file_path( 'inc/fse-political-theme-info-page/class-theme-info.php' );

require_once get_theme_file_path( '/inc/customizer.php' );	

?>