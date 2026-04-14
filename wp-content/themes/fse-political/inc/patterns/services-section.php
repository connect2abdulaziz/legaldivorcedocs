<?php 
/**
 * Default Services Section
 */
return array(
	'title'      => esc_html__( 'Services Section', 'fse-political' ),
	'categories' => array( 'fse-political', 'Services Section' ),
	'content'    => '<!-- wp:group {"layout":{"type":"constrained","contentSize":"80%"}} -->
<div class="wp-block-group"><!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"align":"center","className":"wow fadeInUp","style":{"elements":{"link":{"color":{"text":"var:preset|color|extra-primary"}}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"extra-primary","fontSize":"small","fontFamily":"fse-political-poppins"} -->
<p class="has-text-align-center wow fadeInUp has-extra-primary-color has-text-color has-link-color has-fse-political-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:500">Our Party Vision</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","className":"wow fadeInUp","fontFamily":"fse-political-poppins"} -->
<h2 class="wp-block-heading has-text-align-center wow fadeInUp has-fse-political-poppins-font-family">Together We Are <br>Stronger</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"10px"} -->
<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"className":"wow fadeInUp services-inner-box"} -->
<div class="wp-block-columns wow fadeInUp services-inner-box"><!-- wp:column {"verticalAlignment":"center","className":"services-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"10px"}}} -->
<div class="wp-block-column is-vertically-aligned-center services-box" style="border-radius:10px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)"><!-- wp:image {"id":63,"width":"80px","sizeSlug":"full","linkDestination":"none","align":"center","className":"main-service-icon","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized main-service-icon" style="margin-bottom:var(--wp--preset--spacing--50)"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon1.png' ) ) .'" alt="" class="wp-image-63" style="width:80px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"20px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontFamily":"fse-political-poppins"} -->
<h3 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:20px;font-style:normal;font-weight:600"><a href="#">Get Involved</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#403f3f"},"elements":{"link":{"color":{"text":"#403f3f"}}}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#403f3f">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting<br>industry</p>
<!-- /wp:paragraph -->

<!-- wp:image {"lightbox":{"enabled":false},"id":71,"width":"20px","sizeSlug":"full","linkDestination":"custom","align":"center","className":"service-btn","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized service-btn" style="margin-top:var(--wp--preset--spacing--50)"><a href="#"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon5.png' ) ) .'" alt="" class="wp-image-71" style="width:20px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"services-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"10px"}}} -->
<div class="wp-block-column is-vertically-aligned-center services-box" style="border-radius:10px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)"><!-- wp:image {"id":64,"width":"80px","sizeSlug":"full","linkDestination":"none","align":"center","className":"main-service-icon","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized main-service-icon" style="margin-bottom:var(--wp--preset--spacing--50)"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon2.png' ) ) .'" alt="" class="wp-image-64" style="width:80px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"20px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontFamily":"fse-political-poppins"} -->
<h3 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:20px;font-style:normal;font-weight:600"><a href="#">We Care About Causes</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#403f3f"},"elements":{"link":{"color":{"text":"#403f3f"}}}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#403f3f">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting<br>industry</p>
<!-- /wp:paragraph -->

<!-- wp:image {"lightbox":{"enabled":false},"id":71,"width":"20px","sizeSlug":"full","linkDestination":"custom","align":"center","className":"service-btn","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized service-btn" style="margin-top:var(--wp--preset--spacing--50)"><a href="#"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon5.png' ) ) .'" alt="" class="wp-image-71" style="width:20px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"services-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"10px"}}} -->
<div class="wp-block-column is-vertically-aligned-center services-box" style="border-radius:10px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)"><!-- wp:image {"id":65,"width":"80px","sizeSlug":"full","linkDestination":"none","align":"center","className":"main-service-icon","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized main-service-icon" style="margin-bottom:var(--wp--preset--spacing--50)"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon3.png' ) ) .'" alt="" class="wp-image-65" style="width:80px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"20px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontFamily":"fse-political-poppins"} -->
<h3 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:20px;font-style:normal;font-weight:600"><a href="#">Our Party Is Growing</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#403f3f"},"elements":{"link":{"color":{"text":"#403f3f"}}}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#403f3f">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting<br>industry</p>
<!-- /wp:paragraph -->

<!-- wp:image {"lightbox":{"enabled":false},"id":71,"width":"20px","sizeSlug":"full","linkDestination":"custom","align":"center","className":"service-btn","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized service-btn" style="margin-top:var(--wp--preset--spacing--50)"><a href="#"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon5.png' ) ) .'" alt="" class="wp-image-71" style="width:20px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"services-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"10px"}}} -->
<div class="wp-block-column is-vertically-aligned-center services-box" style="border-radius:10px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)"><!-- wp:image {"id":66,"width":"80px","sizeSlug":"full","linkDestination":"none","align":"center","className":"main-service-icon","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized main-service-icon" style="margin-bottom:var(--wp--preset--spacing--50)"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon4.png' ) ) .'" alt="" class="wp-image-66" style="width:80px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"20px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontFamily":"fse-political-poppins"} -->
<h3 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:20px;font-style:normal;font-weight:600"><a href="#">We Will Win Elections</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#403f3f"},"elements":{"link":{"color":{"text":"#403f3f"}}}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#403f3f">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting<br>industry</p>
<!-- /wp:paragraph -->

<!-- wp:image {"lightbox":{"enabled":false},"id":71,"width":"20px","sizeSlug":"full","linkDestination":"custom","align":"center","className":"service-btn","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<figure class="wp-block-image aligncenter size-full is-resized service-btn" style="margin-top:var(--wp--preset--spacing--50)"><a href="#"><img src="' . esc_url( get_theme_file_uri( '/assets/images/icon5.png' ) ) .'" alt="" class="wp-image-71" style="width:20px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->',
);