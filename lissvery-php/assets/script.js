document.addEventListener('DOMContentLoaded', () => {

  /* Menú móvil */
  const navToggle = document.getElementById('navToggle');
  const mainNav = document.getElementById('main-nav');

  if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => {
      const isOpen = mainNav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', String(isOpen));
      if (!isOpen) closeAllSubmenus();
    });

    mainNav.querySelectorAll(':scope > a').forEach(link => {
      link.addEventListener('click', () => {
        mainNav.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* Submenús (Hosting, Mi cuenta) */
  const navItems = document.querySelectorAll('.nav-item.has-submenu');

  function closeAllSubmenus() {
    navItems.forEach(item => {
      item.classList.remove('is-open');
      item.querySelector('.nav-parent')?.setAttribute('aria-expanded', 'false');
    });
  }

  navItems.forEach(item => {
    const parentBtn = item.querySelector('.nav-parent');
    if (!parentBtn) return;

    parentBtn.addEventListener('click', () => {
      const willOpen = !item.classList.contains('is-open');
      closeAllSubmenus();
      if (willOpen) {
        item.classList.add('is-open');
        parentBtn.setAttribute('aria-expanded', 'true');
      }
    });

    item.querySelectorAll('.submenu a').forEach(link => {
      link.addEventListener('click', () => {
        mainNav?.classList.remove('is-open');
        navToggle?.setAttribute('aria-expanded', 'false');
        closeAllSubmenus();
      });
    });
  });

  document.addEventListener('click', (e) => {
    navItems.forEach(item => {
      if (!item.contains(e.target)) item.classList.remove('is-open');
    });
  });

  /* Botón volver arriba */
  const toTop = document.getElementById('toTop');
  if (toTop) {
    window.addEventListener('scroll', () => {
      toTop.classList.toggle('is-visible', window.scrollY > 600);
    }, { passive: true });
    toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* Animación de la barra de dirección del hero */
  const typedDomain = document.getElementById('typedDomain');
  const statusStack = document.getElementById('statusStack');
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (typedDomain && !prefersReducedMotion) {
    const fullWord = 'tunegocio';
    let i = 0;
    const typeLoop = () => {
      if (i <= fullWord.length) {
        typedDomain.textContent = fullWord.slice(0, i);
        i++;
        setTimeout(typeLoop, 110);
      } else {
        setTimeout(revealStatuses, 400);
      }
    };
    const revealStatuses = () => {
      if (!statusStack) return;
      statusStack.querySelectorAll('li').forEach((item, index) => {
        setTimeout(() => item.classList.add('is-active'), index * 450);
      });
    };
    setTimeout(typeLoop, 500);
  } else if (typedDomain) {
    typedDomain.textContent = 'tunegocio';
    statusStack?.querySelectorAll('li').forEach(li => li.classList.add('is-active'));
  }

  /* Alternar precios mensual / anual (hosting-personal.php) */
  const toggleButtons = document.querySelectorAll('.toggle-opt');
  const amounts = document.querySelectorAll('.amount[data-monthly]');
  const periods = document.querySelectorAll('.price .period');
  const saveNotes = document.querySelectorAll('.price-save');

  toggleButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      toggleButtons.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      const cycle = btn.dataset.cycle;

      amounts.forEach(amount => {
        const value = cycle === 'anual' ? amount.dataset.yearly : amount.dataset.monthly;
        if (value) amount.textContent = value;
      });
      periods.forEach(period => { period.textContent = cycle === 'anual' ? '/año' : '/mes'; });
      saveNotes.forEach(note => { note.textContent = cycle === 'anual' ? (note.dataset.save || '') : ''; });
    });
  });

  /* Revelado al hacer scroll */
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && !prefersReducedMotion) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(el => observer.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('is-visible'));
  }

  /* Formulario de contacto (demostración) */
  const contactForm = document.getElementById('contactForm');
  const formNote = document.getElementById('formNote');
  if (contactForm && formNote) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const nombre = document.getElementById('nombre').value.trim();
      formNote.textContent = `¡Gracias${nombre ? ', ' + nombre : ''}! Recibimos tu mensaje y te contactaremos muy pronto.`;
      contactForm.reset();
    });
  }

  /* Confirmación antes de eliminar en el panel admin */
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.dataset.confirm)) e.preventDefault();
    });
  });

});
