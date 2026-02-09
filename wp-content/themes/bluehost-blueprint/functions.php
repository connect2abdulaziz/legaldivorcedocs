<?php
/**
 * Bluehost Blueprint functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bluehost\Blueprint
 * @since 1.0.0
 */

// Include the autoloader.
require_once get_template_directory() . '/inc/autoloader.php';

// Initialize theme assets.
Bluehost\Blueprint\Assets::init();

// Initialize theme back-compat class.
Bluehost\Blueprint\Back_Compat::init();

// Initialize the version control system.
Bluehost\Blueprint\Version::init();

/**
 * Show GTranslate language selector in the header.
 * Injects the shortcode into the header template part so it displays even when the header was customized.
 */
add_filter( 'render_block', function ( $block_content, $block ) {
	if ( ! isset( $block['blockName'] ) || $block['blockName'] !== 'core/template-part' ) {
		return $block_content;
	}
	$slug = isset( $block['attrs']['slug'] ) ? $block['attrs']['slug'] : '';
	if ( $slug !== 'header' ) {
		return $block_content;
	}
	if ( ! shortcode_exists( 'gtranslate' ) ) {
		return $block_content;
	}
	$gtranslate_html = '<div class="gtranslate-header-wrap">' . do_shortcode( '[gtranslate]' ) . '</div>';
	// Insert right after the nav so the selector is in the same row as logo and "start your divorce", and stays on the right.
	if ( stripos( $block_content, '</nav>' ) !== false ) {
		$block_content = str_ireplace( '</nav>', '</nav>' . $gtranslate_html, $block_content );
	} elseif ( stripos( $block_content, '</header>' ) !== false ) {
		$block_content = str_ireplace( '</header>', $gtranslate_html . '</header>', $block_content );
	} else {
		$block_content .= $gtranslate_html;
	}
	return $block_content;
}, 10, 2 );

/**
 * Fix "Start your divorce" link to always use current site URL.
 * Content/nav may be stored with absolute URLs (e.g. legaldivorcedocs.local or localhost/legaldivorcedocs);
 * this ensures the link works on whatever domain/path the site is running on.
 */
function bluehost_blueprint_fix_start_divorce_url( $content ) {
	if ( empty( $content ) || strpos( $content, 'start-your-divorce' ) === false ) {
		return $content;
	}
	$correct_url = home_url( '/start-your-divorce/' );
	$content     = preg_replace(
		'#href=(["\'])([^"\']*start-your-divorce/?[^"\']*)\1#',
		'href=$1' . esc_attr( $correct_url ) . '$1',
		$content
	);
	return $content;
}

add_filter( 'the_content', 'bluehost_blueprint_fix_start_divorce_url', 20 );
add_filter( 'render_block', function ( $block_content, $block ) {
	if ( isset( $block['blockName'] ) && $block['blockName'] === 'core/navigation' ) {
		return bluehost_blueprint_fix_start_divorce_url( $block_content );
	}
	return $block_content;
}, 20, 2 );

/**
 * Style and position the GTranslate widget in the header.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! shortcode_exists( 'gtranslate' ) ) {
		return;
	}
	$css = '.gtranslate-header-wrap{display:flex;align-items:center;margin-left:auto;flex-shrink:0;}';
	wp_register_style( 'bluehost-blueprint-gtranslate', false, array() );
	wp_enqueue_style( 'bluehost-blueprint-gtranslate' );
	wp_add_inline_style( 'bluehost-blueprint-gtranslate', $css );
}, 20 );
