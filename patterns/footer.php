<?php
/**
 * Title: footer
 * Slug: radiofrei/footer
 * Categories: hidden
 * Inserter: no
 */
?>
<!-- wp:group {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}},"dimensions":{"minHeight":"90px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"color":{"background":"#000000"}},"textColor":"base-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-2-color has-text-color has-background has-link-color" style="background-color:#000000;min-height:90px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10)"><!-- wp:columns {"isStackedOnMobile":false,"align":"full","style":{"spacing":{"blockGap":{"left":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignfull is-not-stacked-on-mobile" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:column {"verticalAlignment":"center","width":""} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"lightbox":{"enabled":false},"id":564,"width":"46px","height":"46px","scale":"cover","sizeSlug":"full","linkDestination":"custom","className":"rf-play-img"} -->
<figure class="wp-block-image size-full is-resized rf-play-img"><a href="#"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/black.webp" alt="" class="wp-image-564" style="object-fit:cover;width:46px;height:46px"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.1"},"elements":{"link":{"color":{"text":"var:preset|color|accent-5"}}}},"textColor":"accent-5","className":"rf-play-title","fontSize":"small"} -->
<p class="rf-play-title has-accent-5-color has-text-color has-link-color has-small-font-size" style="line-height:1.1"><a href="#">Radio F.R.E.I. Live</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40px"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"textAlign":"center","style":{"color":{"background":"#ffffff00"},"spacing":{"padding":{"left":"0px","right":"0px","top":"0px","bottom":"0px"}},"typography":{"fontSize":"0px"}},"className":"rf-play-button"} -->
<div class="wp-block-button has-custom-font-size rf-play-button" style="font-size:0px"><a class="wp-block-button__link has-background has-text-align-center wp-element-button" style="background-color:#ffffff00;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><img class="wp-image-313" style="width: 40px;" src="https://vvv.radio-frei.de/wp-content/uploads/2024/03/play.webp" alt=""></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"","className":"rf-play-right-col"} -->
<div class="wp-block-column is-vertically-aligned-center rf-play-right-col"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right","verticalAlignment":"bottom"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"color":{"background":"#12121200"},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"rf-play-menu-button is-style-fill","fontSize":"small"} -->
<div class="wp-block-button has-custom-font-size rf-play-menu-button is-style-fill has-small-font-size"><a class="wp-block-button__link has-background wp-element-button" style="background-color:#12121200;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">mehr</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:html -->
<audio class="rf-play-audio"></audio>
<!-- /wp:html --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0.3rem","padding":{"right":"0.2rem","left":"0.4rem"}},"typography":{"fontSize":"0px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0.2rem;padding-left:0.4rem;font-size:0px"><!-- wp:paragraph {"align":"left","style":{"typography":{"fontSize":"12px"}},"textColor":"accent-5","className":"rf-play-current"} -->
<p class="has-text-align-left rf-play-current has-accent-5-color has-text-color" style="font-size:12px">00:00</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="rf-slider"><input class="rf-play-range" type="range" min="0" max="100" value="0"></div>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px"}},"textColor":"accent-5","className":"rf-play-duration"} -->
<p class="rf-play-duration has-accent-5-color has-text-color" style="font-size:12px">00:00</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group alignfull" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.1"},"elements":{"link":{"color":{"text":"var:preset|color|accent-5"}}}},"textColor":"accent-5","className":"rf-play-title-menu","fontSize":"small"} -->
<p class="rf-play-title-menu has-accent-5-color has-text-color has-link-color has-small-font-size" style="line-height:1.1"><a href="#">Radio F.R.E.I. Live</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group alignfull"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center","flexWrap":"wrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"19px"},"spacing":{"padding":{"left":"12px","right":"12px","top":"4px","bottom":"4px"}}},"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" style="border-radius:19px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px">Herunterladen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center","flexWrap":"wrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"19px"},"spacing":{"padding":{"left":"12px","right":"12px","top":"4px","bottom":"4px"}}},"className":"rf-live-button is-style-outline"} -->
<div class="wp-block-button rf-live-button is-style-outline"><a class="wp-block-button__link wp-element-button" style="border-radius:19px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px"><strong>☉</strong> Live</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center","flexWrap":"wrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"19px"},"spacing":{"padding":{"left":"12px","right":"12px","top":"4px","bottom":"4px"}}},"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" style="border-radius:19px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px">Geschwindigkeit</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->