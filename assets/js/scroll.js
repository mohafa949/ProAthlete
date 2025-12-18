
let lastScroll = 0;
const header = document.getElementById('main-header');

window.addEventListener('scroll', () => {
  const currentScroll = window.pageYOffset;

  if (currentScroll <= 0) {
    header.classList.remove('hide');
    return;
  }

  if (currentScroll > lastScroll && !header.classList.contains('hide')) {
    // Scrolling down → hide header
    header.classList.add('hide');
  } else if (currentScroll < lastScroll && header.classList.contains('hide')) {
    // Scrolling up → show header
    header.classList.remove('hide');
  }

  lastScroll = currentScroll;
});

