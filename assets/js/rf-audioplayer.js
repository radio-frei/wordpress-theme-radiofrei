/*
 * radiofrei audio player
 */

const maxTitle = 55;

const audio = document.querySelector('.rf-play-audio');
const playButton = document.querySelector('.rf-play-button');
const playImageFigure = document.querySelector('.rf-play-img');
const playImage = playImageFigure.querySelector('img');
const playImageLink = playImageFigure.querySelector('a');
const playTitle = document.querySelector('.rf-play-title').querySelector('a');
const currentTime = document.querySelector('.rf-play-current');
const durationTime = document.querySelector('.rf-play-duration');
const rangeSlider = document.querySelector('.rf-play-range');

let isPlaying = false;


function playItem(event) {

    playTitle.textContent = 'lade...';

    // get data
    const src = event.target.getAttribute('data-src');
    var title = event.target.getAttribute('data-title');
    const url = event.target.getAttribute('data-url');
    const img = event.target.getAttribute('data-img');

    if (title.length > maxTitle) {
        title = title.substring(0, maxTitle) + '...';
    }

    return new Promise((resolve, reject) => {
        audio.src = src;
        audio
            .play()
            .then(() => {
                isPlaying = true;

                // set data to elements
                playImage.src = img;
                playImage.srcset = '';
                playImageLink.href = url;
                playTitle.textContent = title;
                playTitle.href = url;

                //playButton.textContent = 'Pause';
                playButton.disabled = false;
                rangeSlider.disabled = false;

                resolve();
            })
            .catch((error) => {
                reject(error);
            });
    });
}

function updateTimeDisplay() {
    currentTime.textContent = formatTime(audio.currentTime);
    const value = (audio.currentTime / audio.duration) * 100;
    rangeSlider.value = value;
    drawRangeProgress(value);
}

function drawRangeProgress(value) {
    rangeSlider.style.background = `linear-gradient(to right, #fff ${value}%, #4d4d4d ${value}%)`;
}

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds < 10 ? "0" : ""}${remainingSeconds}`;
}

playButton.addEventListener('click', () => {
    if (isPlaying) {
        audio.pause();
        isPlaying = false;
        //playButton.textContent = 'Play';
    } else {
        audio.play();
        isPlaying = true;
        //playButton.textContent = 'Pause';
    }
});

rangeSlider.addEventListener('input', () => {
    const value = rangeSlider.value;
    drawRangeProgress(value);
    const seekTime = (value / 100) * audio.duration;
    audio.currentTime = seekTime;
});

audio.addEventListener('timeupdate', () => {
    updateTimeDisplay();
});

audio.addEventListener('ended', () => {
    isPlaying = false;
    //playButton.textContent = 'Play';
    currentTime.textContent = '00:00';
    rangeSlider.value = 0;
    drawRangeProgress(0);
});

audio.addEventListener('loadeddata', () => {
    playButton.disabled = false;
    durationTime.textContent = formatTime(audio.duration);
});

audio.addEventListener('emptied', () => {
    playButton.disabled = true;
    currentTime.textContent = '00:00';
    durationTime.textContent = '00:00';
    rangeSlider.value = 0;
    drawRangeProgress(0);
});


