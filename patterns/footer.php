<?php
/**
 * Title: footer
 * Slug: radiofrei/footer
 * Categories: hidden
 * Inserter: no
 */
?>
<!-- wp:group {"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}},"color":{"background":"#000000"},"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"textColor":"base-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-2-color has-text-color has-background has-link-color" style="background-color:#000000;padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"0.5rem","bottom":"0.5rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide" style="margin-top:0.5rem;margin-bottom:0.5rem"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":"46px","height":"46px","scale":"contain","sizeSlug":"full","linkDestination":"custom","className":"rf-play-img"} -->
<figure class="wp-block-image size-full is-resized rf-play-img"><a href="#"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/black.webp" alt="" class="" style="object-fit:contain;width:46px;height:46px"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.1"},"elements":{"link":{"color":{"text":"var:preset|color|accent-5"}}}},"textColor":"accent-5","className":"rf-play-title","fontSize":"small"} -->
<p class="rf-play-title has-accent-5-color has-text-color has-link-color has-small-font-size" style="line-height:1.1"><a href="#">Radio F.R.E.I. Live</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"large","linkDestination":"none","align":"center","className":"rf-play-button rf-disabled rf-grey"} -->
<figure class="wp-block-image aligncenter size-large rf-play-button rf-disabled rf-grey"><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"0.5rem","bottom":"0.2rem"}},"typography":{"fontSize":"0px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group alignwide" style="margin-top:0.5rem;margin-bottom:0.2rem;font-size:0px"><!-- wp:html -->
<div class="rf-play-range-container rf-disabled rf-grey"><input class="rf-play-range" type="range" min="0" max="1000" value="0"></div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"0.2rem","bottom":"0.2rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide" style="margin-top:0.2rem;margin-bottom:0.2rem"><!-- wp:paragraph {"align":"left","style":{"typography":{"fontSize":"12px"},"layout":{"selfStretch":"fixed","flexSize":"50px"}},"textColor":"accent-5","className":"rf-play-current rf-grey"} -->
<p class="has-text-align-left rf-play-current rf-grey has-accent-5-color has-text-color" style="font-size:12px">00:00</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"right","style":{"typography":{"fontSize":"12px"},"layout":{"selfStretch":"fixed","flexSize":"50px"}},"textColor":"accent-5","className":"rf-play-duration rf-grey"} -->
<p class="has-text-align-right rf-play-duration rf-grey has-accent-5-color has-text-color" style="font-size:12px">00:00</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:columns {"isStackedOnMobile":false,"align":"wide"} -->
<div class="wp-block-columns alignwide is-not-stacked-on-mobile"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"lightbox":{"enabled":false},"sizeSlug":"large","linkDestination":"custom","align":"left","className":"rf-download-button rf-disabled rf-grey"} -->
<figure class="wp-block-image alignleft size-large rf-download-button rf-disabled rf-grey"><a href="#"><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M2 12h2v5h16v-5h2v5c0 1.11-.89 2-2 2H4a2 2 0 0 1-2-2zm10 3l5.55-5.46l-1.42-1.41L13 11.25V2h-2v9.25L7.88 8.13L6.46 9.55z'/%3E%3C/svg%3E" alt="Download"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","align":"center","className":"rf-close-player-button"} -->
<figure class="wp-block-image aligncenter size-large rf-close-player-button"><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Cpath fill='none' stroke='white' stroke-width='2' d='m2 8.35l10.173 9.823L21.997 8'/%3E%3C/svg%3E" alt="Schließen"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":""} -->
<div class="wp-block-column is-vertically-aligned-top"><!-- wp:image {"lightbox":{"enabled":false},"sizeSlug":"large","linkDestination":"none","align":"right","className":"rf-close-footer-button"} -->
<figure class="wp-block-image alignright size-large rf-close-footer-button"><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m0 16H5V5h14zM17 8.4L13.4 12l3.6 3.6l-1.4 1.4l-3.6-3.6L8.4 17L7 15.6l3.6-3.6L7 8.4L8.4 7l3.6 3.6L15.6 7z'/%3E%3C/svg%3E" alt="Beenden"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:html -->
<audio class="rf-play-audio"></audio>
<!-- /wp:html -->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->