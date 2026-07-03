(function () {
  'use strict';

  const header = document.getElementById('siteHeader');
  const year = document.getElementById('currentYear');
  const navLinks = document.querySelectorAll('.nav-link');
  const navbarCollapse = document.getElementById('mainNavbar');

  if (year) year.textContent = new Date().getFullYear();

  const onScroll = () => {
    if (!header) return;
    header.classList.toggle('header-scrolled', window.scrollY > 24);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Lightweight reveal animation. No animation library needed.
  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealItems.forEach((item) => observer.observe(item));
  } else {
    revealItems.forEach((item) => item.classList.add('is-visible'));
  }

  // Close mobile menu after click.
  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      if (!navbarCollapse || !navbarCollapse.classList.contains('show')) return;
      const instance = bootstrap.Collapse.getOrCreateInstance(navbarCollapse);
      instance.hide();
    });
  });

  // Active navigation state.
  const sections = [...document.querySelectorAll('main section[id]')];
  const setActive = () => {
    const current = sections.reduce((active, section) => {
      const top = section.getBoundingClientRect().top;
      if (top <= 120) return section.id;
      return active;
    }, sections[0] ? sections[0].id : '');

    navLinks.forEach((link) => {
      const id = link.getAttribute('href')?.replace('#', '');
      link.classList.toggle('active', id === current);
    });
  };
  setActive();
  window.addEventListener('scroll', setActive, { passive: true });
})();
