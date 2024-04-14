/*
 * radiofrei ajax 1.0
 */

(function () {

    // setup progressbar
    topbar.config({
        barColors: { '0': 'red' },
    })

    window.addEventListener('load', () => {
        history.replaceState({ url: window.location.href }, '', window.location.href);
        checkForDatePicker()
    });

    // close burger menu & reenable scroll
    function resetBurgerMenu() {
        const containers = document.querySelectorAll('.wp-block-navigation__responsive-container');
        containers.forEach(i => {
            i.classList.remove('is-menu-open', 'has-modal-open');
            i.setAttribute('aria-hidden', 'true');
        });
        const dialogs = document.querySelectorAll('.wp-block-navigation__responsive-dialog');
        dialogs.forEach(i => {
            i.removeAttribute('aria-modal');
            i.removeAttribute('role');
        })
        document.documentElement.classList.remove('has-modal-open');
        document.body.setAttribute('overflow', '');
    }

    // links mit ajax aufrufen außer downloads
    document.addEventListener('click', (event) => {
        const origin = event.target.closest('a[href^="' + document.location.origin + '"]');
        // download links ohne ajax
        if (origin && !origin.hasAttribute('download')) {
            event.preventDefault();
            resetBurgerMenu();
            ajaxFetch(origin.href, window.location.href !== origin.href);
        }
    });

    // Suche mit ajax
    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[action^="' + document.location.origin + '"]');
        if (form) {
            event.preventDefault();
            resetBurgerMenu();
            // FormData erstellen, um die Formulardaten zu erfassen
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();
            const url = `${form.action}?${queryString}`;
            ajaxFetch(url, window.location.href !== url);
        }
    });

    window.addEventListener('popstate', (event) => {
        if (event.state) {
            ajaxFetch(event.state.url, false);
        }
        //needs debugging?: 
        event.stopImmediatePropagation();
    });

    function ajaxFetch(url, pushState) {
        // show progressbar
        topbar.show();
        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    throw new Error('ajax response was not ok: ' + response.statusText);
                }
                return response.text();
            })
            .then((html) => {
                const newDocument = new DOMParser().parseFromString(html, 'text/html');
                if (pushState) history.pushState({ url: url }, '', url);

                /*
                 * css klassen des footers anpassen
                 * die klassennamen für inline styles in <head> und <body> werden von wordpress dynamisch generiert (nummer angehängt) 
                 * daher die klassennamen des alten footers durch die des neuen ersetzen
                 * die eigenen rf- klassen nicht ersetzen, da sonst player state nicht mehr stimmt
                 */

                oldFooterElements = document.querySelector('footer').getElementsByTagName('*');
                newFooterElements = newDocument.querySelector('footer').getElementsByTagName('*');

                for (let i = 0; i < oldFooterElements.length; i++) {

                    let oldElement = oldFooterElements[i];
                    let newElement = newFooterElements[i];

                    let rf = '';
                    if (oldElement.classList.contains('rf-disabled')) rf += 'rf-disabled ';
                    if (oldElement.classList.contains('rf-grey')) rf += 'rf-grey ';

                    newElement.classList.remove('rf-disabled', 'rf-grey');
                    oldElement.className = rf + newElement.className;
                }

                /*
                 * head und main tauschen
                 */
                const oldMain = document.querySelector('main');
                const newMain = newDocument.querySelector('main');

                // footer player offen lassen wenn offen
                if (oldMain.classList.contains('rf-main-player')) {
                    newMain.classList.add('rf-main-player');
                }

                document.documentElement.replaceChild(newDocument.head, document.head);
                oldMain.parentNode.replaceChild(newMain, oldMain);

                // wenn kalender button auf seite, setup datepicker
                checkForDatePicker()

                /*
                 * an den anfang der neuen Seite scrollen
                 */
                scroll(0, 0);
                topbar.hide();
            })
            .catch(error => {
                console.error('error in ajax request:', error);
                topbar.hide();
            });
    }


    /*
     * Funktionen für spezifische Seiten
     */

    // Filter Sendungen A-Z
    window.rf_filterBroadcasts = function (event) {
        const filter = event.target;
        var filterValue = filter.value.toUpperCase();
        var entries = document.querySelectorAll('.is-rf-sendereihe');
        entries.forEach(function (entry) {
            var text = entry.querySelector('h2').textContent.toUpperCase();
            if (text.indexOf(filterValue) > -1) {
                entry.style.display = '';
            } else {
                entry.style.display = 'none';
            }
        });
    }

    // Kalender Wochenprogramm
    function checkForDatePicker() {
        const calendarButton = document.querySelector('#rf-datepicker');
        if (calendarButton) {
            // Date Picker initialisieren
            jQuery(calendarButton).datepicker({
                dateFormat: 'yy-mm-dd',
                // showButtonPanel: true,
                // changeMonth: true,
                // changeYear: true,
                showAnim: '',
                onSelect: function (dateText) {
                    // val setzen, damit button nicht mit dateText beschriftet wird
                    jQuery(calendarButton).val("Kalender");
                    // URL mit dem ausgewählten Datum aktualisieren und Seite neu laden
                    const url = window.location.origin + window.location.pathname + '?' + 'rfstart=' + dateText;
                    ajaxFetch(url, window.location.href !== url);
                }
            });
        }
    }


})();