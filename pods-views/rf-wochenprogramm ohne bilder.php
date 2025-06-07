<?php

/**
 * Radio F.R.E.I. Sendeplanung
 * pods view für den pods-anzeigen-block des wochenprogramms
 */

$rfstart = isset($_GET['rfstart']) ? $_GET['rfstart'] : '';

if (!($date = date_create_immutable($rfstart, wp_timezone()))) {
    $date = date_create_immutable('now', wp_timezone());
}

$monday = $date->modify('Monday this week');
$sunday = $date->modify('Sunday this week');

$params = array(
    'select' => 't.start, t.name, t.permalink, t.bild',
    'where'  => "DATE(t.start) BETWEEN DATE('" . $monday->format('Y-m-d') . "') AND DATE('" . $sunday->format('Y-m-d') . "')",
    'limit'  => -1
);

$rows = pods('sendetermin', $params)->data();

?>

<div class="wp-block-buttons is-horizontal is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo get_permalink(); ?>?rfstart=<?php echo $monday->modify('-7 days')->format('Y-m-d'); ?>">❮</a></div>
    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo get_permalink(); ?>">Diese Woche</a></div>
    <input type="image" id="rf-datepicker" class="wp-block-button__link wp-element-button" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' %3E%3Cpath fill='white' d='M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z' /%3E%3C/svg%3E" style="width: auto;outline: none;">
    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo get_permalink(); ?>?rfstart=<?php echo $monday->modify('+7 days')->format('Y-m-d'); ?>">❯</a></div>
</div>

<?php

if (!empty($rows)) {

    // sendetermine nach wochentag gruppieren
    $rows_by_weekday = array(
        1 => array(),
        2 => array(),
        3 => array(),
        4 => array(),
        5 => array(),
        6 => array(),
        7 => array()
    );

    foreach ($rows as $row) {
        $weekday = (date_create($row->start, wp_timezone())->format('N'));
        $rows_by_weekday[$weekday][] = $row;
    }

?>
    <div class="wp-block-columns alignfull has-small-font-size">
        <?php
        foreach ($rows_by_weekday as $rows_for_one_weekday) {
            if (!empty($rows_for_one_weekday)) {
                // neue spalte mit tabelle
        ?>
                <div class="wp-block-column" style="padding:3px">
                    <figure class="wp-block-table is-style-stripes" style="border-bottom:0;">
                        <table class="has-border-color has-contrast-2-border-color" style="border-width:1px">
                            <?php
                            foreach ($rows_for_one_weekday as $key => $row) {
                                $url = site_url() . '/sendung/' . $row->permalink;
                                $img = wp_get_attachment_image_url($row->bild);
                                if ($key == 0) {
                                    // erste zeile: kopfzeile
                            ?>
                                    <thead>
                                        <tr>
                                            <th colspan="2"><?php echo rf_get_weekday($row->start); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                } // ende if $key == 0
                                    ?>
                                    <tr>
                                        <td colspan="2" style="line-height:10px;"><?php echo rf_strip_date($row->start) . ' Uhr'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none;vertical-align:top;">
                                            <div class="rf-broadcast-title">
                                                <a href="<?php echo $url; ?>"><?php echo $row->name; ?></a>
                                            </div>
                                        </td>
                                        <td style="border-left:none;width:24px;line-height:0;vertical-align:top;padding:0.2em;">
                                            <?php
                                            // es wird von der Startzeit nur die Stunde gecheckt (H00)
                                            $file_src = ABSPATH . 'programm/' . mysql2date('Y/m/d/Y m d H00', $row->start) . ' radiofrei.mp3';
                                            // gibt es einen mitschnitt?
                                            if (file_exists($file_src)) {
                                                $src = site_url('/programm/') . mysql2date('Y/m/d/Y m d H00', $row->start) . ' radiofrei.mp3';
                                                $title = $row->name . ' - ' . rf_strip_time($row->start) . ', ' . rf_strip_date($row->start) . ' Uhr';
                                            ?>
                                                <img style="margin-top:0px;" onclick="rf_playItem(event)" data-src="<?php echo $src; ?>" data-title="<?php echo $title; ?>" data-url="<?php echo $url; ?>" data-img="<?php echo $img; ?>" class="rf-circle-play-button" width="24" height="24" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E" />
                                            <?php
                                            } // ende if
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                            } // ende foreach $rows_for_one_weekday
                                ?>
                                    </tbody>
                        </table>
                    </figure>
                </div>
        <?php
            } // ende if !empty $rows_for_one_weekday
        } // ende foreach $rows_by_weekday
        ?>
    </div>
<?php
} // ende if !empty $rows
else {
?>
    <div class="wp-block-group is-content-justification-center is-nowrap is-layout-flex wp-container-core-group-layout-7 wp-block-group-is-layout-flex">
        <p class="has-text-align-left">Es gibt keine Sendungen in dieser Woche.</p>
    </div>
<?php
}
