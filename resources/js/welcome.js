let slideIndex = 0;
const slides = document.querySelectorAll('.slide');
let autoSlideInterval;

function showSlide(n) {
    if (slides.length === 0) return;
    slideIndex = (n + slides.length) % slides.length;
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === slideIndex);
    });
}

function plusSlides(n) {
    showSlide(slideIndex + n);
    resetAutoSlide();
}

function autoSlide() {
    showSlide(slideIndex + 1);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(autoSlide, 4000);
}

// Initialize
showSlide(slideIndex);
autoSlideInterval = setInterval(autoSlide, 4000);
autoSlideInterval = setInterval(autoSlide, 4000);