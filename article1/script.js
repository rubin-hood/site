/* --------------------------------- Auswählen der Hamburger- und Mobile-Menü-Elemente --------------------------------- */
const hamburgerMenu = document.querySelector('.hamburger-menu'); 
// Wählt das HTML-Element mit der Klasse 'hamburger-menu' aus und speichert es in der Variable 'hamburgerMenu'.

const mobileMenu = document.querySelector('.mobile-menu'); 
// Wählt das HTML-Element mit der Klasse 'mobile-menu' aus und speichert es in der Variable 'mobileMenu'.

/* --------------------------------- Event Listener für das Hamburger-Menü --------------------------------- */
hamburgerMenu.addEventListener('click', () => { 
    // Fügt einen Klick-Event-Listener zum Hamburger-Menü hinzu. Jedes Mal, wenn das Hamburger-Menü angeklickt wird, wird die Funktion ausgeführt.
    
    mobileMenu.classList.toggle('open'); 
    // Schaltet die Klasse 'open' im 'mobileMenu' um. Wenn die Klasse 'open' vorhanden ist, wird sie entfernt, und wenn sie nicht vorhanden ist, wird sie hinzugefügt. Dadurch wird das mobile Menü sichtbar oder unsichtbar gemacht.
});


// Schließen des Menüs beim Klicken außerhalb des Menüs
document.addEventListener('click', (event) => {
    // Prüfen, ob der Klick außerhalb des Menüs und nicht auf das Hamburger-Menü erfolgt ist
    if (!mobileMenu.contains(event.target) && !hamburgerMenu.contains(event.target)) {
        mobileMenu.classList.remove('open');
    }
});



/*---------------------------------- Steuerung der Header-Transparenz beim Scrollen ----------------------------------*/
/* Variable zur Speicherung der letzten Scrollposition */
let lastScrollTop = 0; 
let header = document.querySelector('header'); 
/* Selektiert das Header-Element */

window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop; 
    /* Ermittelt die aktuelle Scrollposition */

    if (scrollTop > lastScrollTop) {
        // Beim Scrollen nach unten: Header wird transparent
        header.classList.add('header-transparent');
    } else if (scrollTop === 0) {
        // Wenn bis ganz nach oben gescrollt: Header wird wieder sichtbar
        header.classList.remove('header-transparent');
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; 
    /* ScrollTop darf nicht negativ werden */
});

/*---------------------------------- Ende Steuerung der Header-Transparenz beim Scrollen ----------------------------------*/







window.addEventListener('load', function() {
    const posts = document.querySelectorAll('.post'); /* Wählt alle Elemente mit der Klasse "post" aus */
    for (let i = 0; i < 3 && i < posts.length; i++) { /* Schleife, die die ersten drei Beiträge durchläuft */
        setTimeout(() => {
            posts[i].classList.add('visible'); /* Fügt die Klasse "visible" hinzu, um den Beitrag sichtbar zu machen */
        }, i * 300); /* Zeitverzögerung für die Animation der Beiträge, damit sie nacheinander erscheinen */
    }
});

document.addEventListener('scroll', function() {
    const posts = document.querySelectorAll('.post'); /* Wählt alle Elemente mit der Klasse "post" aus */
    const scrollPosition = window.scrollY + window.innerHeight; /* Berechnet die aktuelle Scrollposition und die Höhe des Fensters */

    posts.forEach((post, index) => {
        if (post.getBoundingClientRect().top + window.scrollY < scrollPosition) { /* Überprüft, ob der Beitrag im sichtbaren Bereich des Fensters ist */
            setTimeout(() => {
                post.classList.add('visible'); /* Macht den Beitrag sichtbar, wenn er in den sichtbaren Bereich scrollt */
            }, (index + 3) * 100); /* Zeitverzögerung für die Animation der Beiträge, damit sie nacheinander beim Scrollen erscheinen */
        }
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.src;  // Hier direkt das src-Attribut nutzen
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }
});
