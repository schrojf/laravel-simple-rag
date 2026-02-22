import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-landing-animate]').forEach((el) => {
        el.classList.add('landing-fade');
    });
    document.querySelectorAll('[data-landing-animate-delay]').forEach((el) => {
        el.classList.add('landing-fade-delay');
    });
    document.querySelectorAll('[data-landing-animate-delay-2]').forEach((el) => {
        el.classList.add('landing-fade-delay-2');
    });
});
