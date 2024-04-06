<?php

/**
 * Radio F.R.E.I. Sendeplanung
 * pods view für den pods-anzeigen-block des wochenprogramms
 */


/**
 * gibt html für das play icon zurück, wenn eine sendung zum nachhören vorhanden ist
 * TODO: audio tag zurückgeben, um wiedergabe direkt aus dem programmschema zu starten?
 */
function rf_get_play_icon($dateTime)
{
    // für später, url der audiodatei:
    // siehe https://developer.wordpress.org/reference/functions/wp_audio_shortcode/ (mit hooks)
    // oder:
    // $loggingfileurl = site_url('/logging/') . mysql2date('Y/m/d/Y m d H00', $dateTime) . ' radiofrei.mp3';
    // <audio controls preload="none"><source src="$loggingfileurl" type="audio/mpeg">Dein Browser kann kein HTML5-Audio.</audio>

    $loggingfile = ABSPATH . 'programm/' . mysql2date('Y/m/d/Y m d H00', $dateTime) . ' radiofrei.mp3';
    if (file_exists($loggingfile)) {
        return <<<HTML
<img width="16" height="16" style="width:16px;vertical-align:text-bottom;" src="http://localhost/wpdev3/wp-content/uploads/2024/02/play_icon.png" alt="">
HTML;
    }
}



$rfstart = isset($_GET['rfstart']) ? $_GET['rfstart'] : '';

if (!($date = date_create_immutable($rfstart, wp_timezone()))) {
    $date = date_create_immutable('now', wp_timezone());
}

$monday = $date->modify('Monday this week');
$sunday = $date->modify('Sunday this week');

$params = array(
    'select' => 't.start, t.ende, t.name, t.permalink',
    'where'  => "DATE(t.start) BETWEEN DATE('" . $monday->format('Y-m-d') . "') AND DATE('" . $sunday->format('Y-m-d') . "')",
    'limit'  => -1
);

$rows = pods('sendetermin', $params)->data();

?>

<div class="wp-block-buttons is-horizontal is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo get_permalink(); ?>?rfstart=<?php echo $monday->modify('-7 days')->format('Y-m-d'); ?>">❮</a></div>
    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo get_permalink(); ?>">Diese Woche</a></div>
    <input type="image" id="rf-datepicker" class="wp-block-button__link wp-element-button" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' %3E%3Cpath fill='white' d='M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z' /%3E%3C/svg%3E" style="outline: none;">
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
                    <figure class="wp-block-table is-style-stripes">
                        <table class="has-border-color has-contrast-2-border-color" style="border-width:1px">
                            <?php
                            foreach ($rows_for_one_weekday as $key => $row) {
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
                                        <td colspan="2"><?php echo rf_strip_date($row->start) . ' Uhr'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-right:none;">
                                            <a href="<?php echo site_url() . '/sendung/' . $row->permalink; ?>"><?php echo $row->name; ?></a>
                                        </td>
                                        <td style="border-left:none;vertical-align:top;width:16px;">
                                            <a href="<?php echo site_url() . '/sendung/' . $row->permalink; ?>"><?php echo rf_get_play_icon($row->start); ?></a>
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
