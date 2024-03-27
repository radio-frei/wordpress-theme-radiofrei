/*
 * radiofrei ajax 1.0
 */

topbar.config({
    barColors: { '0': 'red' },
})

window.addEventListener('load', (event) => {

    // debug
    console.log('load event ajax');

    history.replaceState({ url: window.location.href }, '', window.location.href);
});

document.addEventListener('click', (event) => {
    const origin = event.target.closest('a[href^="' + document.location.origin + '"]');
    if (origin) {
        event.preventDefault();
        // close burger menu & reenable scroll
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
        ajaxFetch(origin.href, window.location.href !== origin.href);
    }
});

window.addEventListener('popstate', (event) => {
    if (event.state) ajaxFetch(event.state.url, false);
    //needs debugging?: 
    event.stopImmediatePropagation();
});

function ajaxFetch(url, pushState) {
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

            //BUG: der dom wird beim öffnen des mobilen players geändert und daher stimmen alter und neuer footer nicht mehr überein
            // tausch der klassen führt zu falschen zuordnungen
            // FIX: elemente nicht im dom verschieben, sondern aus- und einblenden

            oldFooterElements = document.querySelector('footer').getElementsByTagName('*');
            newFooterElements = newDocument.querySelector('footer').getElementsByTagName('*');


            for (let i = 0; i < oldFooterElements.length; i++) {

                let oldElement = oldFooterElements[i];
                let newElement = newFooterElements[i];

                // console.log('alt  : ' + oldElement.className);
                // console.log('neu  : ' + newElement.className);

                let rf = '';
                if (oldElement.classList.contains('rf-disabled')) rf += 'rf-disabled ';
                if (oldElement.classList.contains('rf-disabled-grey')) rf += 'rf-disabled-grey ';

                // console.log('rf is: ' + rf);

                newElement.classList.remove('rf-disabled', 'rf-disabled-grey');

                // console.log('neu  : ' + newElement.className);

                oldElement.className = rf + newElement.className;


                // console.log('res  : ' + oldElement.className);
                // console.log('----------');

            }

            /*
             * head und main tauschen
             */
            const
                oldMain = document.querySelector('main'),
                newMain = newDocument.querySelector('main');

            document.documentElement.replaceChild(newDocument.head, document.head);

            oldMain.parentNode.replaceChild(newMain, oldMain);

            /*
             * an den anfang der neuen Seite scrollen
             */
            scroll(0, 0);
        })
        .catch(error => {
            console.error('error in ajax request:', error);
        });
    topbar.hide();
}
