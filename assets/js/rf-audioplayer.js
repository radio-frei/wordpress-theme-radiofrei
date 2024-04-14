/*
 * radiofrei audio player 1.0
 */

(function () {

    /*
     *  variables and constants
     *  ACHTUNG: nur Footer Elemente als const, alle Elemente außerhalb des Footers müssen
     *  bei Bedarf jeweils neu selektiert werden, da sie durch ajax ersetzt werden (main etc.)
     */

    let isPlaying = false;
    let isOpen = false;
    let isLive = false;
    let rafID = null;
    let title = '';
    const maxTitle = 100;

    const imgPlay = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E";
    const imgPause = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M14 19h4V5h-4M6 19h4V5H6z'/%3E%3C/svg%3E";

    const audio = document.querySelector('.rf-play-audio');
    const playButtonImage = document.querySelector('.rf-play-button img');
    const image = document.querySelector('.rf-play-img img');
    const imageLink = document.querySelector('.rf-play-img a');
    const titleLink = document.querySelector('.rf-play-title a');
    const current = document.querySelector('.rf-play-current');
    const duration = document.querySelector('.rf-play-duration');
    const playRange = document.querySelector('.rf-play-range');
    const playRangeContainer = document.querySelector('.rf-play-range-container');
    const closePlayerButton = document.querySelector('.rf-close-player-button');
    const closeFooterButton = document.querySelector('.rf-close-footer-button');
    const downloadButtonLink = document.querySelector('.rf-download-button a');
    const footer = document.querySelector('footer');

    /*
     * init
     */

    titleLink.textContent = '';
    downloadButtonLink.setAttribute('download', '');

    // kann nicht in gutenberg gesetzt werden, da dort kein Zugriff nur auf die Links
    titleLink.classList.add('rf-disabled');
    imageLink.classList.add('rf-disabled');


    /*
    * functions
    */

    function toggleFooter() {
        if (isOpen) {
            // minimize footer
            footer.classList.replace('rf-footer-player-open', 'rf-footer-player');
            isOpen = false;
            titleLink.classList.add('rf-disabled');
            imageLink.classList.add('rf-disabled');
            playRangeContainer.classList.add('rf-disabled');
            footer.addEventListener('click', toggleFooter);
        } else {
            // maximize footer
            footer.classList.replace('rf-footer-player', 'rf-footer-player-open');
            isOpen = true;
            titleLink.classList.remove('rf-disabled');
            imageLink.classList.remove('rf-disabled');
            if (!isLive) {
                playRangeContainer.classList.remove('rf-disabled');
            }
            footer.removeEventListener('click', toggleFooter);
        }
    }

    window.rf_playItem = function (event) {

        playButtonImage.src = imgPlay;

        // get data
        const src = event.target.getAttribute('data-src');
        title = event.target.getAttribute('data-title');
        const url = event.target.getAttribute('data-url');
        const img = event.target.getAttribute('data-img');

        //early feedback
        image.src = img;
        imageLink.href = url;
        titleLink.textContent = 'Lade...';
        titleLink.href = url;

        // open player
        // main muss neu selektiert werden, da es durch ajax getauscht wird und verfällt
        document.querySelector('main').classList.add('rf-main-player');
        footer.classList.add('rf-footer-player');
        if (!isOpen) {
            footer.addEventListener('click', toggleFooter);
        }

        return new Promise((resolve, reject) => {
            audio.src = src;
            audio
                .play()
                .then(() => {
                    if (title.length > maxTitle) {
                        title = title.substring(0, maxTitle) + '...';
                    }
                    titleLink.textContent = title;
                    playButtonImage.src = imgPause;
                    if ('mediaSession' in navigator) {
                        navigator.mediaSession.metadata = new MediaMetadata({
                            title: title,
                            artist: 'Radio F.R.E.I.',
                            artwork: [{ src: img }]
                        });
                    }
                    requestAnimationFrame(updatePlayProgress);
                    isPlaying = true;
                    resolve();
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    function togglePlay() {
        if (isPlaying) {
            // pause
            audio.pause();
            playButtonImage.src = imgPlay;
            cancelAnimationFrame(rafID);
            isPlaying = false;
        } else {
            // play
            audio.play();
            playButtonImage.src = imgPause;
            requestAnimationFrame(updatePlayProgress);
            isPlaying = true;
        }
    }

    function updatePlayProgress() {
        if (!playRangeContainer.classList.contains('rf-grey')) {
            setPlayRange((audio.currentTime / audio.duration) * 1000);
        }
        current.textContent = formatTime(audio.currentTime);
        rafID = requestAnimationFrame(updatePlayProgress);
    }

    function drawRangeProgress(value) {
        playRange.style.setProperty('--range-progress-width', value + '%');
    }

    function setPlayRange(value) {
        playRange.value = value;
        drawRangeProgress(value / 10);
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

    playButtonImage.addEventListener('click', (event) => {
        togglePlay();
        event.stopPropagation();
    });

    playRange.addEventListener('input', () => {
        drawRangeProgress(playRange.value / 10);
        current.textContent = formatTime(playRange.value * audio.duration / 1000);
        if (!audio.paused) {
            cancelAnimationFrame(rafID);
        }
    });

    playRange.addEventListener('change', () => {
        audio.currentTime = (playRange.value / 1000) * audio.duration;
        if (!audio.paused) {
            requestAnimationFrame(updatePlayProgress);
        }
    });

    closePlayerButton.addEventListener('click', (event) => {
        toggleFooter();
        event.stopPropagation();
    });

    closeFooterButton.addEventListener('click', () => {
        audio.src = '';
        // main muss neu selektiert werden, da es durch ajax getauscht wird und verfällt
        document.querySelector('main').classList.remove('rf-main-player');
        footer.classList.remove('rf-footer-player', 'rf-footer-player-open');
        titleLink.textContent = '';
        titleLink.classList.add('rf-disabled');
        imageLink.classList.add('rf-disabled');
        isPlaying = false;
        isOpen = false;
    });

    /*
    * audio event listeners
    */
    audio.addEventListener('loadeddata', () => {
        playButtonImage.parentElement.classList.remove('rf-grey', 'rf-disabled');
        current.classList.remove('rf-grey');
        duration.classList.remove('rf-grey');
        if (audio.duration !== Infinity) {
            isLive = false;
            playRangeContainer.classList.remove('rf-grey');
            if (isOpen) {
                playRangeContainer.classList.remove('rf-disabled');
            }
            downloadButtonLink.href = audio.src;
            downloadButtonLink.parentElement.classList.remove('rf-grey', 'rf-disabled');
        } else {
            isLive = true;
        }
        duration.textContent = formatTime(audio.duration);
    });

    audio.addEventListener('ended', () => {
        isPlaying = false;
        playButtonImage.src = imgPlay;
        cancelAnimationFrame(rafID);
        current.textContent = '00:00';
        setPlayRange(0);
    });

    audio.addEventListener('emptied', () => {
        playButtonImage.parentElement.classList.add('rf-grey', 'rf-disabled');
        current.classList.add('rf-grey');
        duration.classList.add('rf-grey');
        playRangeContainer.classList.add('rf-grey', 'rf-disabled');
        current.textContent = '00:00';
        duration.textContent = '00:00';
        setPlayRange(0);
        cancelAnimationFrame(rafID);
        downloadButtonLink.href = '#';
        downloadButtonLink.parentElement.classList.add('rf-grey', 'rf-disabled');
    });

    audio.addEventListener('seeking', () => {
        titleLink.textContent = 'Suche...';
    });

    audio.addEventListener('seeked', () => {
        titleLink.textContent = title;
    });


    /*
    * Media Session API
    */
    if ('mediaSession' in navigator) {
        navigator.mediaSession.setActionHandler('play', togglePlay);

        navigator.mediaSession.setActionHandler('pause', togglePlay);

        navigator.mediaSession.setActionHandler('seekbackward', (details) => {
            audio.currentTime = audio.currentTime - (details.seekOffset || 10);
        });

        navigator.mediaSession.setActionHandler('seekforward', (details) => {
            audio.currentTime = audio.currentTime + (details.seekOffset || 10);
        });

        navigator.mediaSession.setActionHandler('seekto', (details) => {
            if (details.fastSeek && 'fastSeek' in audio) {
                audio.fastSeek(details.seekTime);
                return;
            }
            audio.currentTime = details.seekTime;
        });

        navigator.mediaSession.setActionHandler('stop', () => {
            audio.currentTime = 0;
            setPlayRange(0);
            current.textContent = '00:00';
            if (isPlaying) {
                playButtonImage.src = imgPlay;
                cancelAnimationFrame(rafID);
                isPlaying = false;
            }
        });
    }

})();
