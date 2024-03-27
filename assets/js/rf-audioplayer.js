/*
 * radiofrei audio player 1.0
 */

(function () {

    /*
     *  variables and constants
     */
    console.log('run player function');

    let isPlaying = false,
        isOpen = false;

    const
        maxTitle = 55,

        imgPlay = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M10 16.5v-9l6 4.5M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2'/%3E%3C/svg%3E",
        imgPlayMobile = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M8 5.14v14l11-7z'/%3E%3C/svg%3E",
        imgPause = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M15 16h-2V8h2m-4 8H9V8h2m1-6A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2'/%3E%3C/svg%3E",
        imgPauseMobile = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M14 19h4V5h-4M6 19h4V5H6z'/%3E%3C/svg%3E",

        audio = document.querySelector('.rf-play-audio'),
        playButton = document.querySelector('.rf-play-button img'),
        playImage = document.querySelector('.rf-play-img img'),
        playImageLink = document.querySelector('.rf-play-img a'),
        playTitle = document.querySelector('.rf-play-title a'),
        currentTime = document.querySelector('.rf-play-current'),
        durationTime = document.querySelector('.rf-play-duration'),
        rangeSlider = document.querySelector('.rf-play-range'),
        silderContainer = document.querySelector('.rf-slider'),
        silderDesktopRow = document.querySelector('.rf-slider-desktop-row'),
        silderMobileRow = document.querySelector('.rf-slider-mobile-row'),
        timeMobileRow = document.querySelector('.rf-time-mobile-row'),
        playCols = document.querySelector('.rf-play-cols'),
        playColUtils = document.querySelector('.rf-play-col-utils'),
        mobileCols = document.querySelector('.rf-mobile-cols'),
        closeButton = document.querySelector('.rf-footer-close'),
        footer = document.querySelector('footer'),

        mqlMobile = window.matchMedia('(max-width: 781px)');


    /*
     * init
     */

    playTitle.textContent = '';

    // initial positioning
    if (mqlMobile.matches) {
        adaptScreen(mqlMobile);
    }

    // show player after initial positioning to reduce flicker
    //footerContainer.style.opacity = '1';
    //playCols.classList.remove('rf-hide');


    /*
    * functions
    */

    function disableOnMobile(disable) {
        if (disable) {
            playTitle.classList.add('rf-disabled');
            playImageLink.classList.add('rf-disabled');
            rangeSlider.classList.add('rf-disabled');
        } else {
            playTitle.classList.remove('rf-disabled');
            playImageLink.classList.remove('rf-disabled');
            rangeSlider.classList.remove('rf-disabled');
        }
    }

    function disableAndGreyout(disable) {
        if (disable) {
            playButton.parentElement.classList.add('rf-disabled-grey');
            currentTime.classList.add('rf-disabled-grey');
            durationTime.classList.add('rf-disabled-grey');
            silderContainer.classList.add('rf-disabled-grey');
        } else {
            playButton.parentElement.classList.remove('rf-disabled-grey');
            currentTime.classList.remove('rf-disabled-grey');
            durationTime.classList.remove('rf-disabled-grey');
            if (audio.duration !== Infinity) {
                silderContainer.classList.remove('rf-disabled-grey');
            }
        }
    }

    function adaptScreen(x) {
        if (x.matches) {
            // mobile
            silderMobileRow.appendChild(silderContainer);
            timeMobileRow.appendChild(currentTime);
            timeMobileRow.appendChild(durationTime);
            mobileCols.appendChild(playColUtils);
            isPlaying ? setPlayIcon(false) : setPlayIcon(true);
            playButton.parentElement.parentElement.style.flexBasis = '40px';
            playButton.parentElement.parentElement.style.flexGrow = '0';
            disableOnMobile(true);
            footer.addEventListener('click', toggleFooter);
        } else {
            // desktop
            playCols.appendChild(playColUtils);
            silderDesktopRow.appendChild(currentTime);
            silderDesktopRow.appendChild(silderContainer);
            silderDesktopRow.appendChild(durationTime);
            isPlaying ? setPlayIcon(false) : setPlayIcon(true);
            playButton.parentElement.parentElement.style.flexBasis = '0';
            playButton.parentElement.parentElement.style.flexGrow = '1';
            disableOnMobile(false);
            footer.removeEventListener('click', toggleFooter);
            if (isOpen) toggleFooter();
        }
    }

    function toggleFooter() {
        if (isOpen) {
            footer.style.height = '90px';
            isOpen = false;
            if (mqlMobile.matches) {
                disableOnMobile(true);
                footer.addEventListener('click', toggleFooter);
            }
        } else {
            footer.style.height = '172px';
            isOpen = true;
            disableOnMobile(false);
            footer.removeEventListener('click', toggleFooter);
        }
    }

    window.playItem = function (event) {

        //playButton.src = imgPlay;
        setPlayIcon(true);

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
                    //playButton.src = imgPause;
                    setPlayIcon(false);
                    resolve();
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    function setPlayIcon(play) {
        if (play) {
            playButton.src = mqlMobile.matches ? imgPlayMobile : imgPlay;
        } else {
            playButton.src = mqlMobile.matches ? imgPauseMobile : imgPause;
        }
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

    mqlMobile.addEventListener('change', () => {
        adaptScreen(mqlMobile);
    });

    playButton.addEventListener('click', (event) => {
        if (isPlaying) {
            audio.pause();
            isPlaying = false;
            //playButton.src = imgPlay;
            setPlayIcon(true);
        } else {
            audio.play();
            isPlaying = true;
            //playButton.src = imgPause;
            setPlayIcon(false);
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
        disableAndGreyout(false);
        durationTime.textContent = formatTime(audio.duration);
    });

    audio.addEventListener('timeupdate', () => {
        currentTime.textContent = formatTime(audio.currentTime);
        if (!silderContainer.classList.contains('rf-disabled-grey')) {
            const value = (audio.currentTime / audio.duration) * 100;
            setRangeSlider(value);
        }
    });

    audio.addEventListener('ended', () => {
        isPlaying = false;
        //playButton.src = imgPlay;
        setPlayIcon(true);
        currentTime.textContent = '00:00';
        setRangeSlider(0);
    });

    audio.addEventListener('emptied', () => {
        disableAndGreyout(true);
        currentTime.textContent = '00:00';
        durationTime.textContent = '00:00';
        setRangeSlider(0);
    });

})();
