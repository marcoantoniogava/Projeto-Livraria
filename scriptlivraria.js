let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const nextButton = document.getElementById('next');
const prevButton = document.getElementById('prev');
const progressBarFill = document.querySelector('.progress-bar-fill');

let slideInterval;
let progressInterval;

function showSlide(index) {
 
    slides.forEach((slide) => {
        slide.classList.remove('active');
        slide.style.display = 'none';
    });

    slides[index].style.display = 'block';
    setTimeout(() => {
        slides[index].classList.add('active');
    }, 10);

    resetProgressBar();
}

function resetProgressBar() {
    progressBarFill.style.width = '0%';
    progressBarFill.style.backgroundColor = '#fff';
    progressBarFill.style.transition = 'none';

    progressBarFill.offsetHeight;

    setTimeout(() => {
        progressBarFill.style.transition = 'width 5s linear';
        progressBarFill.style.width = '100%';
        progressBarFill.style.backgroundColor = '#ffdf00';
    }, 10);
}

function autoSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function resetAutoSlideInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(autoSlide, 5000);
}

nextButton.addEventListener('click', () => {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
    resetAutoSlideInterval();
});

prevButton.addEventListener('click', () => {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
    resetAutoSlideInterval();
});

showSlide(currentSlide);

slideInterval = setInterval(autoSlide, 5000);
