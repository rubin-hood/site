/* --------------------------------- Auswählen der Hamburger- und Mobile-Menü-Elemente --------------------------------- */
const hamburgerMenu = document.querySelector('.hamburger-menu'); 
// Wählt das HTML-Element mit der Klasse 'hamburger-menu' aus und speichert es in der Variable 'hamburgerMenu'.

const mobileMenu = document.querySelector('.mobile-menu'); 
// Wählt das HTML-Element mit der Klasse 'mobile-menu' aus und speichert es in der Variable 'mobileMenu'.

const closeMenu = document.querySelector('.close-menu'); 
// Wählt das Schließen-Symbol im mobilen Menü aus

/* --------------------------------- Event Listener für das Hamburger-Menü --------------------------------- */
hamburgerMenu.addEventListener('click', () => { 
    // Fügt einen Klick-Event-Listener zum Hamburger-Menü hinzu
    mobileMenu.classList.toggle('open'); 
    // Schaltet die Klasse 'open' im 'mobileMenu' um, damit das Menü ein- und ausgeblendet wird
});

/* --------------------------------- Event Listener für das Schließen-Symbol --------------------------------- */
closeMenu.addEventListener('click', () => {
    mobileMenu.classList.remove('open'); 
    // Schließt das mobile Menü beim Klicken auf das Schließen-Symbol
});

// Schließen des Menüs beim Klicken außerhalb des Menüs
document.addEventListener('click', (event) => {
    if (!mobileMenu.contains(event.target) && !hamburgerMenu.contains(event.target) && !closeMenu.contains(event.target)) {
        mobileMenu.classList.remove('open');
    }
});

/*---------------------------------- Steuerung der Header-Transparenz beim Scrollen ----------------------------------*/
let lastScrollTop = 0; 
let header = document.querySelector('header'); 

window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
        header.classList.add('header-transparent');
    } else if (scrollTop === 0) {
        header.classList.remove('header-transparent');
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});

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









// Inhalte für verschiedene Seiten
const pages = {
    home: "<h2>Willkommen auf der Startseite!</h2><p>Dies ist die Startseite.</p>",
    about: "<h2>Über uns</h2><p>Informationen über unser Unternehmen.</p>",
    services: "<h2>Dienste</h2><p>Unsere angebotenen Dienstleistungen.</p>",
    contact: "<h2>Kontakt</h2><p>Hier können Sie uns kontaktieren.</p>",
};

// Funktion zum Laden des Seiteninhalts
function navigateTo(page) {
    // Ändere den Hauptinhalt
    document.getElementById('main-content').innerHTML = pages[page] || pages['home'];
    
    // Ändere die URL in der Adressleiste
    window.history.pushState({ page: page }, "", page);
}

// Füge ein Ereignis hinzu, um den Inhalt bei Vorwärts/Rückwärts-Navigation zu aktualisieren
window.onpopstate = function(event) {
    if (event.state && event.state.page) {
        navigateTo(event.state.page);
    } else {
        navigateTo('home');
    }
};

// Lade die Startseite beim ersten Laden der Seite
document.addEventListener('DOMContentLoaded', function () {
    navigateTo('home');
});
