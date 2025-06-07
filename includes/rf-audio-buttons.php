<?php

/**
 * Radio F.R.E.I.
 * 
 * - füge startseiten beiträgen, die audio enthalten, audio buttons hinzu
 * - ersetze audio tags durch audio buttons
 * - live button
 */

/**
 * Mit post id in post_content nach audio tags suchen, wenn audio gefunden:
 * button data attributes mit länge und url etc. geben, die dann von js für
 * player verwendet werden (siehe dlf audio buttons)
 * 
 */

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
                $src = wp_get_attachment_url($id);
                $title = get_the_title();
                $url = get_permalink();
                $img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                $caption = 'Hören ' . wp_get_attachment_metadata($id)['length_formatted'];
                return rf_create_audio_button($src, $title, $url, $img, $caption);
            } else {
                // kein Audio file im player gesetzt, button ausblenden
                $block_content = '';
            }
        } else {
            // kein audio block in beitrag, button ausblenden
            $block_content = '';
        }
    } elseif (!is_admin() && !empty($block['attrs']['className']) && strpos($block['attrs']['className'], 'rf-live-button') !== false) {
        // TODO: get parameter from settings for live stream url
        $live_src = 'http://streaming.fueralle.org/Radio-F.R.E.I';
        $live_title = 'Radio F.R.E.I. Live';
        $live_url = get_home_url();
        $live_img = get_theme_file_uri('assets/images/live.webp');
        $live_caption = 'Live';
        $block_content = rf_create_audio_button($live_src, $live_title, $live_url, $live_img, $live_caption);
    }
    return $block_content;
}
add_filter('render_block_core/button', 'rf_add_audio_data_to_button', 10, 2);


/**
 * helper, suche ersten audio block recursiv
 */
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


/**
 * in allen audio blöcken audio tags durch audio buttons ersetzen
 */
function rf_replace_audio_with_button($block_content, $block)
{
    if (!is_admin()) {
        // wenn audio datei zugewiesen ist, ermittle src, länge und titel und ersetze durch audio button
        if (isset($block['attrs']['id'])) {
            $id = $block['attrs']['id'];
            $src = wp_get_attachment_url($id);
            $title = get_the_title();
            $url = get_permalink();
            $img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            $caption = 'Hören ' . wp_get_attachment_metadata($id)['length_formatted'];
            $html =
                '<div class="wp-block-buttons is-content-justification-left is-layout-flex wp-block-buttons-is-layout-flex">' .
                rf_create_audio_button($src, $title, $url, $img, $caption) .
                '</div>';
            return $html;
        }
    }
    return $block_content;
}
add_filter('render_block_core/audio', 'rf_replace_audio_with_button', 10, 2);


/**
 * einen audio button erstellen
 * Achtung: erstellt nur den einen Button, nicht den container wp-block-buttons
 */
function rf_create_audio_button($src, $title, $url, $img, $caption)
{
    $button_style = 'is-style-fill';
    //$button_style = 'is-style-outline';
    $img_src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='21' height='21' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E";
    $html =
        '<div class="wp-block-button ' . $button_style . '">
            <div onclick="rf_playItem(event)" data-src="' . $src . '" data-title="' . esc_html($title) . '" data-url="' . $url . '" data-img="' . $img . '" class="wp-block-button__link wp-element-button" style="border-radius:19px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px"><img style="vertical-align:bottom;margin-left:-5px;margin-bottom:1px;" src="' . $img_src . '">' . $caption . '</div>
        </div>';
    return $html;
}
