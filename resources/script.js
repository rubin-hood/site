function setupMenu() {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeMenu = document.querySelector('.close-menu');

    if (hamburgerMenu && mobileMenu && closeMenu) {

        hamburgerMenu.addEventListener('click', () => { 
            mobileMenu.classList.toggle('open'); 
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('open'); 
        });

        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && !hamburgerMenu.contains(event.target) && !closeMenu.contains(event.target)) {
                mobileMenu.classList.remove('open');
            }
        });
    }
}

let lastScrollTop = 0; 
let header;

function setupScrollListener() {
    header = document.querySelector('header');

    // Sicherstellen, dass das Header-Element existiert
    if (header) {
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Überprüfen, ob die Seite nach unten gescrollt wird
            if (scrollTop > lastScrollTop) {
                header.classList.add('header-transparent');
            } else if (scrollTop === 0) {
                // Bei Rückkehr zum Anfang des Dokuments den Header wieder normal machen
                header.classList.remove('header-transparent');
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Scroll-Position aktualisieren
        });
    } else {
        console.error('Header-Element nicht gefunden');
    }
}

// Lade die Funktion setupScrollListener beim Laden der Seite
window.addEventListener('load', function() {
    setupMenu();
    setupScrollListener();

    // Anzeigen der ersten 3 Posts mit Verzögerung
    const posts = document.querySelectorAll('.post'); 
    for (let i = 0; i < 3 && i < posts.length; i++) {
        setTimeout(() => {
            posts[i].classList.add('visible');
        }, i * 500);
    }
});

document.addEventListener('scroll', function() {
    const posts = document.querySelectorAll('.post');
    const scrollPosition = window.scrollY + window.innerHeight;

    posts.forEach((post, index) => {
        if (post.getBoundingClientRect().top + window.scrollY < scrollPosition) {
            setTimeout(() => {
                post.classList.add('visible');
            }, index * 300);
        }
    });
});

/*---------------------------------- Lazy Loading für Bilder ----------------------------------*/
document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.src;
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }
});
