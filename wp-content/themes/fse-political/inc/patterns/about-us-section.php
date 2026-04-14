<?php 
/**
 * Default About Section
 */
return array(
	'title'      => esc_html__( 'About Section', 'fse-political' ),
	'categories' => array( 'fse-political', 'About Section' ),
	'content'    => '<!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group"><!-- wp:columns {"verticalAlignment":"center","className":"about-sectionnn","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"backgroundColor":"background"} -->
<div class="wp-block-columns about-sectionnn are-vertically-aligned-center has-background-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"center","width":"60%","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:60%"><!-- wp:group {"className":"about-box","layout":{"type":"constrained","contentSize":"70%"}} -->
<div class="wp-block-group about-box"><!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"className":"wow fadeInUp","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontSize":"small","fontFamily":"fse-political-poppins"} -->
<p class="wow fadeInUp has-foreground-color has-text-color has-link-color has-fse-political-poppins-font-family has-small-font-size" style="font-style:normal;font-weight:500">Our Skills</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"wow fadeInUp","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontSize":"35px","lineHeight":"1.5"}},"textColor":"foreground","fontFamily":"fse-political-poppins"} -->
<h3 class="wp-block-heading wow fadeInUp has-foreground-color has-text-color has-link-color has-fse-political-poppins-font-family" style="font-size:35px;line-height:1.5">The Best Way to Grow<br>Your Political Business</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"wow fadeInUp","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"textColor":"foreground"} -->
<p class="wow fadeInUp has-foreground-color has-text-color has-link-color">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.<br><br>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"extra-primary","className":"wow fadeInUp"} -->
<div class="wp-block-button wow fadeInUp"><a class="wp-block-button__link has-extra-primary-background-color has-background wp-element-button">Read More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":99,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="' . esc_url( get_theme_file_uri( '/assets/images/about-image.png' ) ) . '" alt="" class="wp-image-99"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
);