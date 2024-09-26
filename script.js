/* --------------------------------- Auswählen der Hamburger- und Mobile-Menü-Elemente --------------------------------- */
// Diese Funktion wird aufgerufen, nachdem der Header geladen wurde
function setupMenu() {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeMenu = document.querySelector('.close-menu');

    if (hamburgerMenu && mobileMenu && closeMenu) {
        /* --------------------------------- Event Listener für das Hamburger-Menü --------------------------------- */
        hamburgerMenu.addEventListener('click', () => { 
            mobileMenu.classList.toggle('open'); 
        });

        /* --------------------------------- Event Listener für das Schließen-Symbol --------------------------------- */
        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('open'); 
        });

        // Schließen des Menüs beim Klicken außerhalb des Menüs
        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && !hamburgerMenu.contains(event.target) && !closeMenu.contains(event.target)) {
                mobileMenu.classList.remove('open');
            }
        });
    }
}

/*---------------------------------- Steuerung der Header-Transparenz beim Scrollen ----------------------------------*/
let lastScrollTop = 0; 
let header; // Header wird später definiert

function setupScrollListener() {
    header = document.querySelector('header'); // Header wird neu geladen, nachdem er durch fetch() eingefügt wurde

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            header.classList.add('header-transparent');
        } else if (scrollTop === 0) {
            header.classList.remove('header-transparent');
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
}

/*---------------------------------- Beiträge Animationen beim Laden und Scrollen ----------------------------------*/
window.addEventListener('load', function() {
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
            }, index * 300); // Hier wird der Index genutzt, um die Animation nacheinander zu starten
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

/*---------------------------------- Header und Footer dynamisch laden ----------------------------------*/
document.addEventListener("DOMContentLoaded", function () {
    // Header laden
    fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
            setupMenu(); // Menü-Event-Listener einrichten
            setupScrollListener(); // Scroll-Listener erst nach Laden des Headers einrichten
        })
        .catch(error => console.error('Fehler beim Laden des Headers:', error));

    // Footer laden
    fetch('footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        })
        .catch(error => console.error('Fehler beim Laden des Footers:', error));
})
