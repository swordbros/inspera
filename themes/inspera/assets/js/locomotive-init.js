"use strict";

const locoScroll = new LocomotiveScroll({
  el: document.querySelector('.smooth-scroll'),
  smooth: true,
  // class: 'is-inview',
  getSpeed: true,
  getDirection: true,
  smartphone: {
    smooth: false,
  },
  tablet: {
    smooth: false,
  },
});

document.querySelectorAll('a[data-scroll-to]').forEach(link => {
  link.addEventListener('click', function(event) {
    event.preventDefault();
    const target = this.getAttribute('data-scroll-to');
    locoScroll.scrollTo(document.getElementById(target));
  });
});

/**
 * add is-inview independent from loco
 */
const elements = document.querySelectorAll('.content-section');

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-inview');
    } else {
      entry.target.classList.remove('is-inview');
    }
  });
});

elements.forEach(element => observer.observe(element));
