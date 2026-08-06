import Swiper from 'swiper';
import { Autoplay, Navigation, Pagination, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const adminMenuToggle = document.querySelector('.admin-menu-toggle');
const adminSidebar = document.querySelector('#admin-sidebar');
const adminBackdrop = document.querySelector('.admin-sidebar-backdrop');

if (adminMenuToggle && adminSidebar && adminBackdrop) {
    const adminDesktopQuery = window.matchMedia('(min-width: 901px)');
    const closeAdminMenu = () => {
        document.body.classList.remove('admin-menu-open');
        adminMenuToggle.setAttribute('aria-expanded', 'false');
        adminMenuToggle.setAttribute('aria-label', 'Abrir menú de administración');
        adminBackdrop.hidden = true;
        adminSidebar.inert = !adminDesktopQuery.matches;
    };
    const openAdminMenu = () => {
        document.body.classList.add('admin-menu-open');
        adminMenuToggle.setAttribute('aria-expanded', 'true');
        adminMenuToggle.setAttribute('aria-label', 'Cerrar menú de administración');
        adminBackdrop.hidden = false;
        adminSidebar.inert = false;
        adminSidebar.querySelector('nav a')?.focus();
    };

    adminMenuToggle.addEventListener('click', () => adminMenuToggle.getAttribute('aria-expanded') === 'true' ? closeAdminMenu() : openAdminMenu());
    document.querySelectorAll('[data-admin-menu-close]').forEach((button) => button.addEventListener('click', closeAdminMenu));
    adminSidebar.querySelectorAll('nav a').forEach((link) => link.addEventListener('click', closeAdminMenu));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.body.classList.contains('admin-menu-open')) {
            closeAdminMenu();
            adminMenuToggle.focus();
        }
        if (event.key === 'Tab' && document.body.classList.contains('admin-menu-open')) {
            const focusable = [...adminSidebar.querySelectorAll('button, a')];
            const first = focusable[0];
            const last = focusable.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        }
    });
    adminDesktopQuery.addEventListener('change', (event) => {
        if (event.matches) closeAdminMenu();
        adminSidebar.inert = !event.matches;
    });
    adminSidebar.inert = !adminDesktopQuery.matches;
}

const menuToggle = document.querySelector('.mobile-menu-toggle');
const mobileNavigation = document.querySelector('#mobile-navigation');

if (menuToggle && mobileNavigation) {
    const mobileMenuLinks = mobileNavigation.querySelectorAll('a');
    const closeMenu = () => {
        mobileNavigation.hidden = true;
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Abrir menú');
        document.body.classList.remove('menu-open');
    };
    const openMenu = () => {
        mobileNavigation.hidden = false;
        menuToggle.setAttribute('aria-expanded', 'true');
        menuToggle.setAttribute('aria-label', 'Cerrar menú');
        document.body.classList.add('menu-open');
        mobileNavigation.querySelector('a')?.focus();
    };

    menuToggle.addEventListener('click', () => {
        menuToggle.getAttribute('aria-expanded') === 'true' ? closeMenu() : openMenu();
    });
    mobileNavigation.querySelector('[data-menu-close]')?.addEventListener('click', closeMenu);
    mobileMenuLinks.forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !mobileNavigation.hidden) {
            closeMenu();
            menuToggle.focus();
        }
        if (event.key === 'Tab' && !mobileNavigation.hidden) {
            const focusable = [...mobileNavigation.querySelectorAll('a')];
            const first = focusable[0];
            const last = focusable.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        }
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', (event) => {
        if (event.matches) closeMenu();
    });
}

if (document.querySelector('.benefits-swiper')) {
    new Swiper('.benefits-swiper', {
        modules: [Autoplay, Navigation, Pagination, A11y],
        slidesPerView: 1.08,
        spaceBetween: 14,
        grabCursor: true,
        loop: true,
        autoplay: reduceMotion || window.matchMedia('(max-width: 699px)').matches ? false : { delay: 4800, disableOnInteraction: true },
        navigation: { nextEl: '.benefits-next', prevEl: '.benefits-prev' },
        pagination: { el: '.benefits-swiper .swiper-pagination', clickable: true },
        breakpoints: {
            480: { slidesPerView: 1.35, spaceBetween: 18 },
            700: { slidesPerView: 2.1, spaceBetween: 22 },
            1000: { slidesPerView: 2.7, spaceBetween: 26 },
            1280: { slidesPerView: 3.2, spaceBetween: 28 },
        },
    });
}

if (document.querySelector('.product-swiper')) {
    new Swiper('.product-swiper', {
        modules: [Autoplay, Pagination, A11y],
        loop: true,
        effect: 'slide',
        autoplay: reduceMotion || window.matchMedia('(max-width: 699px)').matches ? false : { delay: 5200, disableOnInteraction: true },
        pagination: { el: '.product-swiper .swiper-pagination', clickable: true },
    });
}

const revealItems = document.querySelectorAll('[data-reveal]');
if (reduceMotion || !('IntersectionObserver' in window)) {
    revealItems.forEach((item) => item.classList.add('is-visible'));
} else {
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.14, rootMargin: '0px 0px -35px' });
    revealItems.forEach((item) => revealObserver.observe(item));
}
