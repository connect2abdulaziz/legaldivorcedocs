<?php 
/**
 * Default Header
 */
return array(
	'title'      => esc_html__( 'Default Header', 'fse-political' ),
	'categories' => array( 'fse-political', 'header' ),
	'content'    => '<!-- wp:group {"className":"menu-header","style":{"spacing":{"padding":{"right":"0","left":"0","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"background","layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group menu-header has-background-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--40);padding-right:0;padding-bottom:var(--wp--preset--spacing--40);padding-left:0"><!-- wp:columns {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns" style="margin-top:0;margin-bottom:0"><!-- wp:column {"verticalAlignment":"center","className":"columnn-1","width":"15%"} -->
<div class="wp-block-column is-vertically-aligned-center columnn-1" style="flex-basis:15%"><!-- wp:paragraph {"align":"left","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"15px"}},"textColor":"foreground","fontFamily":"fse-political-poppins"} -->
<p class="has-text-align-left has-foreground-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:15px;font-style:normal;font-weight:500"><span class="dashicons dashicons-phone"></span> +12 ( 1234 ) 5566</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"columnn-2","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center columnn-2" style="flex-basis:25%"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontFamily":"fse-political-poppins"} -->
<p class="has-foreground-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:15px;font-style:normal;font-weight:500"><span class="dashicons dashicons-location"></span> 80 Broklyn Golden Street USA</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"columnn-3","width":"20%"} -->
<div class="wp-block-column is-vertically-aligned-center columnn-3" style="flex-basis:20%"><!-- wp:paragraph {"align":"left","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontFamily":"fse-political-poppins"} -->
<p class="has-text-align-left has-foreground-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:15px;font-style:normal;font-weight:500"><span class="dashicons dashicons-email"></span> political123@exmple.com</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"columnn-4","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center columnn-4" style="flex-basis:40%"><!-- wp:social-links {"iconColor":"foreground","iconColorValue":"#ffffff","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"right"}} -->
<ul class="wp-block-social-links has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"pinterest"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"menu-header","style":{"spacing":{"padding":{"right":"0","left":"0","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"},"margin":{"top":"0","bottom":"0"}},"border":{"radius":"0px"}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group menu-header" style="border-radius:0px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-right:0;padding-bottom:var(--wp--preset--spacing--30);padding-left:0"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:group {"className":"logo-box","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group logo-box"><!-- wp:site-logo {"width":80,"shouldSyncIcon":true} /-->

<!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"typography":{"fontSize":"25px","fontStyle":"normal","fontWeight":"700"}},"textColor":"black","fontFamily":"fse-political-poppins"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:navigation {"textColor":"black","overlayTextColor":"extra-primary","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"capitalize","fontSize":"18px"}},"fontFamily":"fse-political-poppins","layout":{"type":"flex","justifyContent":"left"}} -->
<!-- wp:navigation-link {"label":"Home","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"About","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Event","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Blog","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Gallery","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Contact","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Buy Now","type":"link","opensInNewTab":true,"url":"' . esc_url( FSE_POLITICAL_BUY_NOW ) . '","kind":"custom","className":"buy-now-button"} /-->
<!-- /wp:navigation --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"20%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:20%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"extra-primary","textColor":"foreground","style":{"border":{"radius":"5px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontFamily":"fse-political-poppins"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-foreground-color has-extra-primary-background-color has-text-color has-background has-link-color has-fse-political-poppins-font-family wp-element-button" style="border-radius:5px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50);font-style:normal;font-weight:600">Lets Talk</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
);