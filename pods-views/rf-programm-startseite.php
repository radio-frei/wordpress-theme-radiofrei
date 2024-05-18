<?php

/**
 * Radio F.R.E.I. Sendeplanung
 * pods view für die Startseite 'Jetzt auf Sendung'
 */

$params_prev = array(
    'select'  => 't.start, t.name, t.permalink, t.bild',
    'where'   => 't.ende < NOW()',
    'orderby' => 't.ende DESC',
    'limit'   => 1
);

$params_now = array(
    'select'  => 't.start, t.name, t.permalink, t.bild',
    'where'   => 't.start <= NOW() AND t.ende > NOW()',
    'limit'   => 1
);

$params_next = array(
    'select'  => 't.start, t.name, t.permalink, t.bild',
    'where'   => 't.start > NOW()',
    'orderby' => 't.start ASC',
    'limit'   => 1
);

$pods = array(
    pods('sendetermin', $params_prev)->data(),
    pods('sendetermin', $params_now)->data(),
    pods('sendetermin', $params_next)->data()
);

// ausgabe nur, wenn mindestens ein pod nicht leer ist
if (!empty($pods)) {

?>
    <figure class="wp-block-table is-style-stripes" style="border-bottom:0;">
        <table class="has-border-color has-contrast-2-border-color" style="border-width:1px;">
            <thead>
                <tr>
                    <th colspan="3">Aktuelles Programm</th>
                </tr>
            </thead>
            <tbody class="rf-broadcast-title">
                <?php
                foreach ($pods as $pod) {
                    if (!empty($pod)) {
                        foreach ($pod as $row) {
                            $url = site_url() . '/sendung/' . $row->permalink;
                            $img = wp_get_attachment_image_url($row->bild);
                ?>
                            <tr>
                                <td><?php echo rf_strip_date($row->start); ?></td>
                                <td style="border-right:none;">
                                    <a href="<?php echo $url; ?>"><?php echo $row->name; ?></a>
                                </td>
                                <td style="border-left:none;width:24px;line-height:0;vertical-align:top;">
                                    <?php
                                    // es wird von der Startzeit nur die Stunde gecheckt (H00)
                                    $file_src = ABSPATH . 'programm/' . mysql2date('Y/m/d/Y m d H00', $row->start) . ' radiofrei.mp3';
                                    // gibt es einen mitschnitt?
                                    if (file_exists($file_src)) {
                                        $src = site_url('/programm/') . mysql2date('Y/m/d/Y m d H00', $row->start) . ' radiofrei.mp3';
                                        $title = $row->name . ' - ' . rf_strip_time($row->start) . ', ' . rf_strip_date($row->start) . ' Uhr';
                                    ?>
                                        <img onclick="rf_playItem(event)" data-src="<?php echo $src; ?>" data-title="<?php echo $title; ?>" data-url="<?php echo $url; ?>" data-img="<?php echo $img; ?>" class="rf-circle-play-button" style="margin-top:0px;" width="24" height="24" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E" />
                                    <?php
                                    } // ende if file_exists
                                    ?>
                                </td>
                            </tr>
                <?php
                        } // ende foreach ($pod as $row)
                    }  // ende if (!empty($pod))
                } // ende foreach ($pods as $pod)
                ?>
                <tr>
                    <td colspan="3" style="text-align: center;"><a href="radio-hoeren/wochenprogramm">Wochenprogramm -&gt;</a></td>
                </tr>
            </tbody>
        </table>
    </figure>
<?php
} // ende if (!empty($pods))
