/* ════════════════════════════════════════════
   DJANET.JS — Galerie filtres + cursor
════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── Curseur custom ── */
  const cur  = document.getElementById('cur');
  const curR = document.getElementById('cur-r');
  if (cur && curR) {
    document.addEventListener('mousemove', e => {
      cur.style.left  = e.clientX + 'px';
      cur.style.top   = e.clientY + 'px';
      setTimeout(() => {
        curR.style.left = e.clientX + 'px';
        curR.style.top  = e.clientY + 'px';
      }, 60);
    });
  }

  /* ── Filtres galerie ── */
  const filterBtns = document.querySelectorAll('.filter-btn');
  const items      = document.querySelectorAll('.gallery-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Activer le bouton
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;

      items.forEach(item => {
        const cat = item.dataset.category;
        if (filter === 'all' || cat === filter || cat === 'all') {
          item.classList.remove('hidden');
        } else {
          item.classList.add('hidden');
        }
      });
    });
  });

  /* ── Lightbox simple au clic ── */
  items.forEach(item => {
    const img = item.querySelector('img');
    if (!img) return;

    item.addEventListener('click', () => {
      // Créer overlay
      const overlay = document.createElement('div');
      overlay.style.cssText = `
        position:fixed;inset:0;z-index:9000;
        background:rgba(0,0,0,.92);backdrop-filter:blur(8px);
        display:flex;align-items:center;justify-content:center;
        cursor:zoom-out;animation:fadein .25s ease;
      `;

      const imgClone = document.createElement('img');
      imgClone.src = img.src;
      imgClone.alt = img.alt;
      imgClone.style.cssText = `
        max-width:90vw;max-height:90vh;
        object-fit:contain;border:1px solid rgba(245,202,46,.25);
        box-shadow:0 0 80px rgba(212,98,42,.35);
        animation:zoom-in .3s cubic-bezier(.22,1,.36,1) both;
      `;

      // Caption
      const cap = item.querySelector('.item-caption');
      if (cap) {
        const capClone = document.createElement('div');
        capClone.style.cssText = `
          position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);
          text-align:center;font-family:'Cormorant Garamond',serif;
          color:rgba(245,240,232,.85);font-size:1rem;font-style:italic;
        `;
        capClone.textContent = cap.querySelector('h3')?.textContent || '';
        overlay.appendChild(capClone);
      }

      overlay.appendChild(imgClone);
      document.body.appendChild(overlay);

      overlay.addEventListener('click', () => overlay.remove());
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') overlay.remove();
      }, { once: true });
    });
  });

});

/* CSS keyframes injectés dynamiquement */
const style = document.createElement('style');
style.textContent = `
  @keyframes fadein { from{opacity:0} to{opacity:1} }
  @keyframes zoom-in { from{transform:scale(.88);opacity:0} to{transform:none;opacity:1} }
`;
document.head.appendChild(style);
