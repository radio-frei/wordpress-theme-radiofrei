<?php

/**
 * Radio F.R.E.I. Sendeplanung
 * Funktionen für das Programmschema
 *
 */


add_filter('render_block_core/button', 'rf_add_audio_data_to_button', 10, 2);

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
                $length = wp_get_attachment_metadata($id)['length_formatted'];
                $url = wp_get_attachment_url($id);
                $block_content = str_replace('<div ', '<div data-audio-url="' . $url . '" ', $block_content);
                $block_content = str_replace('00:00', $length, $block_content);
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
