/* ═══════════════════════════════════════════════════════════════
   REVO TRAVAUX — shared.js v6
   ═══════════════════════════════════════════════════════════════ */

const WA_NUMBER = '596696381215';
const WA_DEFAULT_MSG = 'Bonjour REVO TRAVAUX, je souhaite une étude de devis pour mes travaux en Martinique.';
const WA_ARTISAN_MSG = 'Bonjour, je suis artisan ou entreprise BTP en Martinique et je souhaite rejoindre le réseau de sous-traitance REVO TRAVAUX.';
let LOGO_COLOR = 'images/site/web/logo-couleur.png';
let LOGO_WHITE = 'images/site/web/logo-blanc.png';

/* ── NAVIGATION ─────────────────────────────────────────────── */
function buildNav(activeSlug) {
  const isHome = activeSlug === '' || activeSlug === 'index';
  const navClass = isHome ? 'nav nav-dark' : 'nav nav-solid';
  const realActive = activeSlug === 'realisations' || activeSlug === 'equipe' || activeSlug === 'avant-apres';

  document.getElementById('main-nav').innerHTML = `
    <div class="artisan-banner">
      <a href="https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(WA_ARTISAN_MSG)}" target="_blank">
        🔨 Entreprises &amp; artisans BTP Martinique ? <span>Devenez partenaire sous-traitant →</span>
      </a>
    </div>
    <nav class="${navClass}" id="mainNav">
      <div class="nav-inner">
        <a href="index.html" class="nav-logo"><img id="navLogoImg" src="${LOGO_COLOR}" alt="REVO TRAVAUX" height="34"></a>
        <div class="nav-links" id="navLinks">
          <a href="index.html" class="nav-link${activeSlug === '' ? ' active' : ''}">Accueil</a>
          <div class="nav-dropdown">
            <a href="services.html" class="nav-link${activeSlug === 'services' ? ' active' : ''}">Services ▾</a>
            <div class="nav-dropdown-menu">
              <div class="dropdown-label">RÉALISÉ PAR NOS ÉQUIPES</div>
              <a href="service-renovation-complete.html" class="dropdown-link">Rénovation complète</a>
              <a href="service-cuisine.html" class="dropdown-link">Cuisine</a>
              <a href="service-salle-de-bain.html" class="dropdown-link">Salle de bain</a>
              <a href="service-electricite.html" class="dropdown-link">Électricité</a>
              <a href="service-plomberie.html" class="dropdown-link">Plomberie</a>
              <div class="dropdown-label" style="margin-top:8px">ÉNERGIE</div>
              <a href="service-renovation-energetique.html" class="dropdown-link">Rénovation énergétique</a>
              <div class="dropdown-label" style="margin-top:8px">AMO / COURTAGE</div>
              <a href="services.html#amo" class="dropdown-link">Coordination de chantier</a>
            </div>
          </div>
          <div class="nav-dropdown">
            <a href="realisations.php" class="nav-link${realActive ? ' active' : ''}">Réalisations ▾</a>
            <div class="nav-dropdown-menu">
              <a href="realisations.php" class="dropdown-link">Nos chantiers</a>
              <a href="avant-apres.html" class="dropdown-link">Avant / Après</a>
              <a href="equipe.html" class="dropdown-link">Notre équipe</a>
            </div>
          </div>
          <a href="blog.php" class="nav-link${activeSlug === 'blog' ? ' active' : ''}">Blog</a>
          <div class="nav-dropdown">
            <a href="zones.html" class="nav-link${activeSlug === 'zones' ? ' active' : ''}">Zones ▾</a>
            <div class="nav-dropdown-menu">
              <a href="zone-fort-de-france.html" class="dropdown-link">Fort-de-France</a>
              <a href="zone-le-lamentin.html" class="dropdown-link">Le Lamentin</a>
              <a href="zone-schoelcher.html" class="dropdown-link">Schoelcher</a>
              <a href="zone-les-trois-ilets.html" class="dropdown-link">Les Trois-Îlets</a>
              <a href="zone-le-robert.html" class="dropdown-link">Le Robert</a>
              <a href="zone-le-marin.html" class="dropdown-link">Le Marin</a>
              <a href="zones.html" class="dropdown-link" style="color:var(--gold);font-weight:700;margin-top:6px">Voir toutes →</a>
            </div>
          </div>
          <a href="faq.html" class="nav-link${activeSlug === 'faq' ? ' active' : ''}">FAQ</a>
          <a href="devis-ia.html" class="nav-link${activeSlug === 'devis-ia' ? ' active' : ''}">🤖 Devis</a>
          <div class="nav-cta">
            <a href="https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(WA_DEFAULT_MSG)}" target="_blank" class="btn btn-primary" style="padding:10px 20px;font-size:0.82rem">Étude de devis gratuite</a>
          </div>
        </div>
        <div class="nav-overlay" id="navOverlay" onclick="closeNav()"></div>
        <button class="nav-toggle" id="navToggle" onclick="toggleNav()" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>`;

  document.body.classList.add('has-banner');

  if (isHome) {
    window.addEventListener('scroll', () => {
      const nav = document.getElementById('mainNav');
      if (!nav) return;
      if (window.scrollY > 80) {
        nav.classList.remove('nav-dark');
        nav.classList.add('nav-solid');
      } else {
        nav.classList.remove('nav-solid');
        nav.classList.add('nav-dark');
      }
    });
  }
}

function toggleNav() {
  const nl = document.getElementById('navLinks');
  const ov = document.getElementById('navOverlay');
  const tb = document.getElementById('navToggle');
  nl.classList.toggle('open');
  ov.classList.toggle('open');
  const isOpen = nl.classList.contains('open');
  document.body.style.overflow = isOpen ? 'hidden' : '';
  if (tb) tb.classList.toggle('is-open', isOpen);
}
function closeNav() {
  document.getElementById('navLinks').classList.remove('open');
  document.getElementById('navOverlay').classList.remove('open');
  document.body.style.overflow = '';
  const tb = document.getElementById('navToggle');
  if (tb) tb.classList.remove('is-open');
}

/* ── FOOTER ─────────────────────────────────────────────────── */
function buildFooter() {
  document.getElementById('main-footer').innerHTML = `
    <footer class="footer"><div class="container">
      <div class="footer-grid">
        <div>
          <div class="footer-brand"><img src="${LOGO_COLOR}" alt="REVO TRAVAUX" height="30"></div>
          <p class="footer-desc">Entreprise de rénovation en Martinique. Nos équipes réalisent vos travaux, nous pilotons la sous-traitance quand c'est utile, et coordonnons votre chantier de A à Z. Étude de devis gratuite sous 24h.</p>
        </div>
        <div><h4>Services</h4>
          <a href="service-renovation-complete.html" class="footer-link">Rénovation complète</a>
          <a href="service-cuisine.html" class="footer-link">Cuisine & salle de bain</a>
          <a href="service-renovation-energetique.html" class="footer-link">Rénovation énergétique</a>
          <a href="service-electricite.html" class="footer-link">Électricité & plomberie</a>
          <a href="services.html" class="footer-link">Tous les services</a>
        </div>
        <div><h4>Ressources</h4>
          <a href="realisations.php" class="footer-link">Réalisations</a>
          <a href="avant-apres.html" class="footer-link">Avant / Après</a>
          <a href="equipe.html" class="footer-link">Notre équipe</a>
          <a href="devis-ia.html" class="footer-link">🤖 Étude de devis</a>
          <a href="blog.php" class="footer-link">Blog</a>
          <a href="faq.html" class="footer-link">FAQ</a>
          <a href="mentions-legales.html" class="footer-link">Mentions légales</a>
        </div>
        <div><h4>Contact</h4>
          <a href="tel:+596696381215" class="footer-link">0696 38 12 15</a>
          <a href="mailto:contact@revotravaux.fr" class="footer-link">contact@revotravaux.fr</a>
          <a href="https://wa.me/${WA_NUMBER}" target="_blank" class="footer-link">WhatsApp →</a>
          <p style="font-size:0.78rem;color:rgba(43,36,23,0.45);margin-top:12px;line-height:1.5">Habitation Case Navire<br>97233 Schoelcher, Martinique</p>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© ${new Date().getFullYear()} REVO TRAVAUX</span>
        <span>Martinique · French Caribbean</span>
      </div>
    </div></footer>`;
}

/* ── WHATSAPP FLOAT ─────────────────────────────────────────── */
function addWA(msg) {
  const text = msg || WA_DEFAULT_MSG;
  document.body.insertAdjacentHTML('beforeend', `
    <a href="https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(text)}" target="_blank" class="wa-float" aria-label="WhatsApp">
      <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>`);
}

/* ── LOCAL SEO SCHEMA (JSON-LD) ────────────────────────────────
   Injecté sur chaque page pour le référencement local :
   rénovation Martinique, courtage travaux Martinique, étude de devis. */
function injectSchema() {
  if (document.getElementById('revo-schema')) return;
  const schema = {
    "@context": "https://schema.org",
    "@type": "HomeAndConstructionBusiness",
    "@id": "https://revotravaux.fr/#organization",
    "name": "REVO TRAVAUX",
    "alternateName": "Revo Travaux Martinique",
    "url": "https://revotravaux.fr/",
    "logo": "https://revotravaux.fr/images/site/web/logo-couleur.png",
    "image": "https://revotravaux.fr/og-image.jpg",
    "description": "REVO TRAVAUX réalise vos travaux de rénovation en Martinique avec ses propres équipes, pilote la sous-traitance d'artisans certifiés et coordonne votre chantier de A à Z (AMO). Étude de devis gratuite sous 24h.",
    "telephone": "+596696381215",
    "email": "contact@revotravaux.fr",
    "priceRange": "€€",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Habitation Case Navire",
      "addressLocality": "Schoelcher",
      "postalCode": "97233",
      "addressRegion": "Martinique",
      "addressCountry": "FR"
    },
    "areaServed": ["Fort-de-France","Le Lamentin","Schoelcher","Saint-Joseph","Le Robert","Le François","Sainte-Marie","Le Marin","Les Trois-Îlets","Le Diamant","Sainte-Anne","Le Vauclin","La Trinité","Rivière-Pilote","Ducos"],
    "sameAs": []
  };
  const s = document.createElement('script');
  s.type = 'application/ld+json';
  s.id = 'revo-schema';
  s.textContent = JSON.stringify(schema);
  document.head.appendChild(s);
}

/* ── REVEAL ON SCROLL ───────────────────────────────────────── */
function initReveals() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
}
document.addEventListener('DOMContentLoaded', initReveals);
if (document.readyState !== 'loading') initReveals();

/* ── FAQ ACCORDION ──────────────────────────────────────────── */
document.addEventListener('click', e => {
  const q = e.target.closest('.faq-q');
  if (!q) return;
  const item = q.closest('.faq-item');
  const wasOpen = item.classList.contains('open');
  item.closest('.faq-list')?.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
  if (!wasOpen) item.classList.add('open');
});

/* ── FILTERS ────────────────────────────────────────────────── */
function initFilters(btnClass, dataAttr) {
  document.querySelectorAll('.' + btnClass).forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.' + btnClass).forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter || btn.getAttribute('data-filter');
      document.querySelectorAll('[' + dataAttr + ']').forEach(card => {
        card.style.display = (filter === 'all' || card.getAttribute(dataAttr) === filter) ? '' : 'none';
      });
    });
  });
}
