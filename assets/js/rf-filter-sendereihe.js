
const filter = document.querySelector('#rf-filter');

if (filter) {
    filter.addEventListener('input', () => {
        var filterValue = filter.value.toUpperCase();
        //var filterValue = this.value.toUpperCase();
        var entries = document.querySelectorAll('.is-rf-sendereihe');

        entries.forEach(function (entry) {
            var text = entry.querySelector('h2').textContent.toUpperCase();
            if (text.indexOf(filterValue) > -1) {
                entry.style.display = '';
            } else {
                entry.style.display = 'none';
            }
        });
    });
}