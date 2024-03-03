<?php

/**
 * Radio F.R.E.I.
 * 
 * Füge startseiten beiträgen, die audio enthalten, audio buttons hinzu
 * Ersetze audio tags durch audio buttons
 * 
 */



/**
 * Mit post id in post_content nach audio tags suchen, wenn audio gefunden:
 * button data attributes mit länge und url etc. geben, die dann von js für
 * player verwendet werden (siehe dlf audio buttons)
 * 
 */
add_filter('render_block_core/button', 'rf_add_audio_data_to_button', 10, 2);
function rf_add_audio_data_to_button($block_content, $block)
{
    // nur buttons mit class rf-audio-button ändern
    if (!is_admin() && !empty($block['attrs']['className']) && strpos($block['attrs']['className'], 'rf-audio-button') !== false) {

        // suche im post_content des posts nach audio blöcken
        global $post;
        $blocks = parse_blocks($post->post_content);
        $block = rf_find_first_audio_block($blocks);
        if ($block) {
            // es gibt einen audio block, ermittle url und länge als data attribute für den button
            if (isset($block['attrs']['id'])) {
                $id = $block['attrs']['id'];
                $block_content = rf_get_block_content_for_button($id);
            } else {
                // kein Audio file im player gesetzt, button ausblenden
                $block_content = '';
            }
        } else {
            // kein audio block in beitrag, button ausblenden
            $block_content = '';
        }
    }
    return $block_content;
}

function rf_find_first_audio_block($blocks)
{
    foreach ($blocks as $block) {
        if ('core/audio' === $block['blockName']) {
            return $block;
        } elseif (!empty($block['innerBlocks'])) {
            rf_find_first_audio_block($block['innerBlocks']);
        }
    }
    return false;
}

// in allen audio blöcken audio tags durch audio buttons ersetzen
add_filter('render_block_core/audio', 'rf_replace_audio_with_button', 10, 2);
function rf_replace_audio_with_button($block_content, $block)
{
    // wenn audio datei zugewiesen ist, ermittle src, länge und titel und ersetze durch audio button
    if (isset($block['attrs']['id'])) {
        $id = $block['attrs']['id'];
        $block_content = rf_get_block_content_for_button($id);
    }
    return $block_content;
}

// daten für audio button ermitteln
function rf_get_block_content_for_button($audio_id)
{
    $src = wp_get_attachment_url($audio_id);
    $title = get_the_title();
    $url = get_permalink();
    $img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
    $length = wp_get_attachment_metadata($audio_id)['length_formatted'];
    return rf_create_audio_button($src, $title, $url, $img, $length);
}

// einen audio button erstellen
function rf_create_audio_button($src, $title, $url, $img, $length)
{
    $html =
        '<div class="wp-block-buttons is-content-justification-left is-layout-flex wp-block-buttons-is-layout-flex rf-audio-button">
            <div class="wp-block-button is-style-fill" onclick="rf_playItem()" data-src="' . $src . '" data-title="' . esc_html($title) . '" data-url="' . $url . '" data-img="' . $img . '"><a class="wp-block-button__link wp-element-button" style="border-radius:19px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px">▶ Hören ' . $length . '</a></div>
        </div>';
    return $html;
}
