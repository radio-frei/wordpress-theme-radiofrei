/*
 * radiofrei audio player 1.0
 */

(function () {

    //console.log('run player function');

    /*
     *  variables and constants
     */

    let isPlaying = false;
    let isOpen = false;

    const maxTitle = 100;

    const imgPlay = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E";
    const imgPause = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M14 19h4V5h-4M6 19h4V5H6z'/%3E%3C/svg%3E";

    const audio = document.querySelector('.rf-play-audio');
    const playButton = document.querySelector('.rf-play-button img');
    const playImage = document.querySelector('.rf-play-img img');
    const playImageLink = document.querySelector('.rf-play-img a');
    const playTitle = document.querySelector('.rf-play-title a');
    const currentTime = document.querySelector('.rf-play-current');
    const durationTime = document.querySelector('.rf-play-duration');
    const rangeSlider = document.querySelector('.rf-play-range');
    const silderContainer = document.querySelector('.rf-slider');
    const closeButton = document.querySelector('.rf-footer-close');
    const footer = document.querySelector('footer');

    /*
     * init
     */

    // TODO: preload live stream?
    playTitle.textContent = '';

    playTitle.classList.add('rf-disabled');
    playImageLink.classList.add('rf-disabled');


    /*
    * functions
    */

    function toggleFooter() {
        if (isOpen) {
            // close footer
            footer.style.height = '72px';
            isOpen = false;
            playTitle.classList.add('rf-disabled');
            playImageLink.classList.add('rf-disabled');
            silderContainer.classList.add('rf-disabled');
            footer.addEventListener('click', toggleFooter);
        } else {
            // open footer
            footer.style.height = '152px';
            isOpen = true;
            playTitle.classList.remove('rf-disabled');
            playImageLink.classList.remove('rf-disabled');
            if (audio.duration !== Infinity) {
                silderContainer.classList.remove('rf-disabled');
            }
            footer.removeEventListener('click', toggleFooter);
        }
    }

    window.rf_playItem = function (event) {

        playButton.src = imgPlay;

        // get data
        const src = event.target.getAttribute('data-src');
        var title = event.target.getAttribute('data-title');
        const url = event.target.getAttribute('data-url');
        const img = event.target.getAttribute('data-img');

        //early feedback
        playImage.src = img;
        playImageLink.href = url;
        playTitle.textContent = 'lade...';
        playTitle.href = url;

        return new Promise((resolve, reject) => {
            audio.src = src;
            audio
                .play()
                .then(() => {
                    isPlaying = true;
                    if (title.length > maxTitle) {
                        title = title.substring(0, maxTitle) + '...';
                    }
                    playTitle.textContent = title;
                    playButton.src = imgPause;
                    // muss neu selektiert werden, da main durch ajax getauscht wird und verfällt
                    document.querySelector('main').classList.add('rf-main-player');
                    footer.classList.add('rf-footer-player');
                    footer.addEventListener('click', toggleFooter);
                    resolve();
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    function drawRangeProgress(value) {
        rangeSlider.style.background = `linear-gradient(to right, #fff ${value}%, #4d4d4d ${value}%)`;
    }

    function setRangeSlider(value) {
        rangeSlider.value = value;
        drawRangeProgress(value);
    }

    function formatTime(seconds) {
        if (seconds === Infinity) {
            return 'Live';
        }
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes < 10 ? "0" : ""}${minutes}:${remainingSeconds < 10 ? "0" : ""}${remainingSeconds}`;
    }


    /*
    * event listeners
    */

    playButton.addEventListener('click', (event) => {
        if (isPlaying) {
            audio.pause();
            isPlaying = false;
            playButton.src = imgPlay;
        } else {
            audio.play();
            isPlaying = true;
            playButton.src = imgPause;
        }
        event.stopPropagation();
    });

    rangeSlider.addEventListener('input', () => {
        const value = rangeSlider.value;
        drawRangeProgress(value);
        const seekTime = (value / 100) * audio.duration;
        audio.currentTime = seekTime;
    });

    closeButton.addEventListener('click', (event) => {
        toggleFooter();
        event.stopPropagation();
    });

    audio.addEventListener('loadeddata', () => {
        playButton.parentElement.classList.remove('rf-grey', 'rf-disabled');
        currentTime.classList.remove('rf-grey');
        durationTime.classList.remove('rf-grey');
        if (audio.duration !== Infinity) {
            silderContainer.classList.remove('rf-grey');
            if (isOpen) silderContainer.classList.remove('rf-disabled');
        }
        durationTime.textContent = formatTime(audio.duration);
    });

    audio.addEventListener('timeupdate', () => {
        currentTime.textContent = formatTime(audio.currentTime);
        if (!silderContainer.classList.contains('rf-grey')) {
            const value = (audio.currentTime / audio.duration) * 100;
            setRangeSlider(value);
        }
    });

    audio.addEventListener('ended', () => {
        isPlaying = false;
        playButton.src = imgPlay;
        currentTime.textContent = '00:00';
        setRangeSlider(0);
    });

    audio.addEventListener('emptied', () => {
        playButton.parentElement.classList.add('rf-grey', 'rf-disabled');
        currentTime.classList.add('rf-grey');
        durationTime.classList.add('rf-grey');
        silderContainer.classList.add('rf-grey', 'rf-disabled');
        currentTime.textContent = '00:00';
        durationTime.textContent = '00:00';
        setRangeSlider(0);
    });

})();
