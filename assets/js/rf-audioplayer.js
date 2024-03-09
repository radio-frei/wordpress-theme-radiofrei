/*
 * radiofrei audio player
 */

const maxTitle = 55;
let isPlaying = false;
let isOpen = false;

const audio = document.querySelector('.rf-play-audio');
const playButton = document.querySelector('.rf-play-button');
const playImageFigure = document.querySelector('.rf-play-img');
const playImage = playImageFigure.querySelector('img');
const playImageLink = playImageFigure.querySelector('a');
const playTitle = document.querySelector('.rf-play-title').querySelector('a');
const currentTime = document.querySelector('.rf-play-current');
const durationTime = document.querySelector('.rf-play-duration');
const rangeSlider = document.querySelector('.rf-play-range');
const silderContainer = document.querySelector('.rf-slider');
const menuButton = document.querySelector('.rf-play-menu-button');
const footer = document.querySelector('footer');

playButton.classList.add('rf-disabled');
currentTime.classList.add('rf-disabled');
durationTime.classList.add('rf-disabled');
silderContainer.classList.add('rf-disabled');
menuButton.classList.add('rf-disabled');
//menuButton.style.display = 'none';

playImage.removeAttribute('srcset');
playTitle.textContent = '';


function playItem(event) {

    playButton.classList.remove('rf-playing');

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
                playButton.classList.add('rf-playing');
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

playButton.addEventListener('click', () => {
    if (isPlaying) {
        audio.pause();
        isPlaying = false;
        playButton.classList.remove('rf-playing');
    } else {
        audio.play();
        isPlaying = true;
        playButton.classList.add('rf-playing');
    }
});

rangeSlider.addEventListener('input', () => {
    const value = rangeSlider.value;
    drawRangeProgress(value);
    const seekTime = (value / 100) * audio.duration;
    audio.currentTime = seekTime;
});

audio.addEventListener('loadeddata', () => {
    playButton.classList.remove('rf-disabled');
    currentTime.classList.remove('rf-disabled');
    durationTime.classList.remove('rf-disabled');
    menuButton.classList.remove('rf-disabled');
    //menuButton.style.display = 'inline-block';
    if (audio.duration !== Infinity) {
        silderContainer.classList.remove('rf-disabled');
    }
    durationTime.textContent = formatTime(audio.duration);
});

audio.addEventListener('timeupdate', () => {
    currentTime.textContent = formatTime(audio.currentTime);
    if (!silderContainer.classList.contains('rf-disabled')) {
        const value = (audio.currentTime / audio.duration) * 100;
        setRangeSlider(value);
    }
});

audio.addEventListener('ended', () => {
    isPlaying = false;
    playButton.classList.remove('rf-playing');
    currentTime.textContent = '00:00';
    setRangeSlider(0);
});

audio.addEventListener('emptied', () => {
    playButton.classList.add('rf-disabled');
    currentTime.classList.add('rf-disabled');
    durationTime.classList.add('rf-disabled');
    silderContainer.classList.add('rf-disabled');
    menuButton.classList.add('rf-disabled');
    currentTime.textContent = '00:00';
    durationTime.textContent = '00:00';
    setRangeSlider(0);
});


menuButton.addEventListener('click', () => {
    if (isOpen) {
        footer.style.height = "90px";
        isOpen = false;
        menuButton.querySelector('a').textContent = 'mehr';
        menuButton.classList.remove('rf-play-menu-open');
    } else {
        footer.style.height = "180px";
        isOpen = true;
        menuButton.querySelector('a').textContent = 'weniger';
        menuButton.classList.add('rf-play-menu-open');
    }
});