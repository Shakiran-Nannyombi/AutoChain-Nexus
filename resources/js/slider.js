document.addEventListener('DOMContentLoaded', function () {
    const thumbnails = document.querySelectorAll('.slider-thumb');
    let currentIndex = 0;
    let intervalId;
    const maxVisible = 6;

    function setActive(index) {
        thumbnails.forEach((thumb, i) => {
            thumb.classList.remove('active', 'visible');
        });

        // Calculate the window of visible thumbnails
        let start = index - Math.floor(maxVisible / 2);
        let end = start + maxVisible;
        if (start < 0) {
            start = 0;
            end = Math.min(maxVisible, thumbnails.length);
        }
        if (end > thumbnails.length) {
            end = thumbnails.length;
            start = Math.max(0, end - maxVisible);
        }

        for (let i = start; i < end; i++) {
            thumbnails[i].classList.add('visible');
        }

        thumbnails[index].classList.add('active');
        currentIndex = index;
    }

    thumbnails.forEach((thumb, i) => {
        thumb.addEventListener('click', () => {
            setActive(i);
            resetInterval();
        });
    });

    function nextSlide() {
        let newIndex = (currentIndex + 1) % thumbnails.length;
        setActive(newIndex);
    }

    function startInterval() {
        intervalId = setInterval(nextSlide, 2000); // 2 seconds
    }

    function resetInterval() {
        clearInterval(intervalId);
        startInterval();
    }

    setActive(0);
    startInterval();
});
