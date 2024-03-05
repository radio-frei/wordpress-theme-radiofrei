jQuery(document).ready(function($) {
    // Date Picker initialisieren
    $('#rf-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showAnim: '',
        onSelect: function (dateText) {
            // URL mit dem ausgewählten Datum aktualisieren und Seite neu laden
            // val setzen, damit button nicht mit dateText beschriftet wird
            $('#rf-datepicker').val("Kalender");
            var urlParams = new URLSearchParams(window.location.search);
            urlParams.set('rfstart', dateText);
            window.location.search = urlParams.toString();
        }
    });

});
