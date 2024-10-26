document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading for all sections except the first one
    const lazyContents = document.querySelectorAll('.lazy-content');
    const lazyImages = document.querySelectorAll('.lazy-content img[data-src]');
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    // Observer for sections
    const sectionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const section = entry.target;
                section.classList.add('visible');
                observer.unobserve(section);
            }
        });
    }, observerOptions);

    // Observer for images
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    }, observerOptions);

    // Start observing
    lazyContents.forEach(section => {
        sectionObserver.observe(section);
    });

    lazyImages.forEach(img => {
        imageObserver.observe(img);
    });
});





