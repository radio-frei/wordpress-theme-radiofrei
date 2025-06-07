<?php

/**
 * Radio F.R.E.I.
 * backend funktionen
 **/


/*
* taxonomy box für sendereihen ändern zu single term
* https://github.com/WebDevStudios/WDS_Taxonomy_Radio
*/
$rf_tax_mb = new Taxonomy_Single_Term('sendereihe', array('sendung'), 'select');
$rf_tax_mb->set('context', 'normal');
$rf_tax_mb->set('metabox_title', 'Sendereihe');
$rf_tax_mb->set('force_selection', true);


/*
 * admin spalten für liste der sendereihen anpassen
 * https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
 */
add_filter('manage_edit-sendereihe_columns', function ($columns) {
    if (!empty($columns) && is_array($columns)) {
        $name = $columns['name'];
        $desc = $columns['description'];
        $slug = $columns['slug'];
        $posts = $columns['posts'];
        unset($columns['name']);
        unset($columns['description']);
        unset($columns['slug']);
        unset($columns['posts']);
        $columns['bild'] = 'Bild';
        $columns['name'] = $name;
        $columns['new_description'] = $desc;
        $columns['user'] = 'Benutzer';
        $columns['slug'] = $slug;
        $columns['posts'] = $posts;
        return $columns;
    }
});

/*
 * inhalt der admin spalten für liste der sendereihen anpassen
 * https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
 */
add_action('manage_sendereihe_custom_column', function ($string, $column_name, $term_id) {
    switch ($column_name) {
        case 'bild':
            echo wp_get_attachment_image(get_term_meta($term_id, 'bild', true), array('60', '60'));
            break;
        case 'user':
            $users = get_term_meta($term_id, 'r_users');
            if (!empty($users) && is_array($users)) {
                foreach ($users as $user) {
                    echo nl2br(get_user_by('ID', $user)->display_name . "\n");
                }
            }
            break;
        case 'new_description':
            echo wp_trim_words(term_description($term_id), 12);
            break;
    }
}, 10, 3);


/*
 * admin spalten für liste der sendungen anpassen, sendetermine und beiträge anzeigen
 */
add_filter('manage_sendung_posts_columns', function ($columns) {
    $col_date = $columns['date'];
    unset($columns['date']);
    $columns['taxonomy-sendereihe'] = 'Sendereihe';
    $columns['start'] = 'Sendetermine';
    $columns['beitraege'] = 'Beiträge';
    $columns['date'] = $col_date;
    return $columns;
});

add_action('manage_sendung_posts_custom_column', function ($column, $post_id) {
    $s_pod = pods('sendung', $post_id);
    if ('start' === $column) {
        $items = $s_pod->field('r_sendetermine');
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                echo nl2br(mysql2date('D, d.m.y, H:i', $item['start']) . " Uhr\n");
            }
        }
    }
    if ('beitraege' === $column) {
        $items = $s_pod->field('r_beitraege');
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                echo nl2br($item['post_title'] . "\n");
            }
        }
    }
}, 10, 2);


/*
 * standard sortierung admin liste der sendeternmine anpassen
 */
function rf_sort_sendetermine_admin($find_params)
{
    // nur wenn keine sortierung gesetzt ist
    if (empty($find_params['orderby'])) {
        $find_params['orderby'] = array('start' => 'DESC');
    }
    return $find_params;
}
add_filter('pods_ui_get_find_params', 'rf_sort_sendetermine_admin', 10, 1);


/**
 *  vor dem speichern einer sendung
 */
function rf_pre_save_sendung($pieces, $is_new_item)
{

    /** 
     * pods bugfix:
     * von gewählten sendeterminen evtl. schon bestehene beziehungen zu anderen sendungen entfernen, 
     * damit sendetermine immer nur eine sendung haben (pods kann one-to-many beziehungen nicht sicherstellen)
     * das muss pre_save passieren
     */

    $st_start = '';

    // ausgewählte sendetermine ermitteln
    $st_ids = $pieces['fields']['r_sendetermine']['value'];
    if (!empty($st_ids) && is_array($st_ids)) {
        foreach ($st_ids as $st_id) {
            // pod object des sendetermins
            $st_pod = pods('sendetermin', $st_id);
            // start des ersten sendertermins speichern zum generieren des sendungsnamens
            if (empty($st_start)) {
                $st_start = $st_pod->display('start');
            }
            // wert des beziehungsfeldes
            $r_sendung = $st_pod->field('r_sendung');
            // wenn sendetermin beziehung hat
            if (!empty($r_sendung)) {
                // beziehung entfernen
                $st_pod->remove_from('r_sendung', $r_sendung['ID']);
            }
        }
    }

    // namen der sendung aus sendereihe und erstem sendetermin generieren
    // post_title in fields_active setzen, um ihn ändern zu können
    if (!isset($pieces['fields_active']['post_title'])) {
        $pieces['fields_active'][] = 'post_title';
    }
    // post_name in fields_active setzen, damit permalink automatisch generiert wird
    if (!isset($pieces['fields_active']['post_name'])) {
        $pieces['fields_active'][] = 'post_name';
    }
    $sendereihe = get_term_by('slug', $_POST['tax_input']['sendereihe'], 'sendereihe')->name;
    $pieces['object_fields']['post_title']['value'] =  $sendereihe . ' - ' . mysql2date('d. F Y', $st_start);

    return $pieces;
}
add_filter('pods_api_pre_save_pod_item_sendung', 'rf_pre_save_sendung', 10, 2);


/**
 * nach dem speichern einer sendung namen und permalinks der ausgewählen sendetermine setzen
 */
function rf_post_save_sendung($pieces, $is_new_item, $id)
{
    $st_ids = $pieces['fields']['r_sendetermine']['value'];
    if (!empty($st_ids) && is_array($st_ids)) {
        $post = get_post($id);
        // sendereihe ermitteln:
        $sendereihe = get_the_terms($id, 'sendereihe')[0];
        $data = array(
            // Titel der Sendung nehmen:
            //'name' => $post->post_title,
            // Titel der Sendereihe nehmen:
            'name' => $sendereihe->name,
            'permalink' => $post->post_name,
            // bild id der sendereihe
            'bild' => get_term_meta($sendereihe->term_id, 'bild', true)
        );
        foreach ($st_ids as $st_id) {
            $pod_st = pods('sendetermin', $st_id);
            $pod_st->save($data);
        }
    }
}
add_action('pods_api_post_save_pod_item_sendung', 'rf_post_save_sendung', 10, 3);


/**
 * vor dem speichern eines sendetermins name und permalink des sendetermins auf die daten der ausgewählten sendung setzen
 */
function rf_pre_save_sendetermin($pieces, $is_new_item)
{
    if (!empty($pieces['fields']['r_sendung']['value'])) {
        $sendung = get_post($pieces['fields']['r_sendung']['value']['0']);
        $sendereihe = get_the_terms($sendung, 'sendereihe')[0];
        // Titel der Sendung nehmen:
        //$pieces['fields']['name']['value'] = $sendung->post_title;
        // Titel der Sendereihe nehmen:
        $pieces['fields']['name']['value'] = $sendereihe->name;
        $pieces['fields']['permalink']['value'] = $sendung->post_name;
        // bild id der sendereihe
        $pieces['fields']['bild']['value'] = get_term_meta($sendereihe->term_id, 'bild', true);
    }
    return $pieces;
}
add_filter('pods_api_pre_save_pod_item_sendetermin', 'rf_pre_save_sendetermin', 10, 2);
