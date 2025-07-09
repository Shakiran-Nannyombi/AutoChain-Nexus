console.log('Welcome page JS loaded.');

document.addEventListener('DOMContentLoaded', function() {
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    let autoSlideInterval;

    function showSlide(n) {
        if (slides.length === 0) return;
        
        // Remove active class from all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        // Update slideIndex
        slideIndex = (n + slides.length) % slides.length;
        
        // Add active class to current slide
        slides[slideIndex].classList.add('active');
    }

    function plusSlides(n) {
        showSlide(slideIndex + n);
        resetAutoSlide();
    }

    function autoSlide() {
        // Generate random slide index different from current
        let randomIndex;
        do {
            randomIndex = Math.floor(Math.random() * slides.length);
        } while (randomIndex === slideIndex && slides.length > 1);
        
        showSlide(randomIndex);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(autoSlide, 3000); // 3 seconds
    }

    // Initialize - make sure slides exist
    if (slides.length > 0) {
        console.log(`Found ${slides.length} slides`);
        showSlide(0); // Show first slide
        autoSlideInterval = setInterval(autoSlide, 3000); // Start auto-slide every 3 seconds
    } else {
        console.log('No slides found with class .slide');
    }

    // Make functions globally accessible if needed
    window.plusSlides = plusSlides;
});