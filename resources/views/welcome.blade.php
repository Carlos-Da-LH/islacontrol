<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IslaControl – Agencia de Software</title>
  <meta name="description" content="IslaControl es una agencia de software que desarrolla soluciones digitales a medida: sistemas de gestión, aplicaciones web y móviles para tu negocio." />
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-dark:  #1a5c45;
      --green-mid:   #2e8b6a;
      --green-light: #4db891;
      --green-pale:  #d4f0e6;
      --white:       #ffffff;
      --gray-50:     #f8fafb;
      --gray-100:    #eef2f5;
      --gray-400:    #94a3b8;
      --gray-600:    #475569;
      --gray-800:    #1e293b;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--gray-800);
      background: var(--white);
      overflow-x: hidden;
    }

    /* ── NAVBAR ── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 5%;
      height: 110px;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(0,0,0,0.06);
      transition: box-shadow .3s;
    }
    nav.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .nav-logo img { height: 100px; }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 2rem;
      list-style: none;
    }
    .nav-links a {
      text-decoration: none;
      font-size: .9rem;
      font-weight: 500;
      color: var(--gray-600);
      transition: color .2s;
    }
    .nav-links a:hover { color: var(--green-mid); }

    .nav-cta {
      background: var(--green-mid);
      color: var(--white) !important;
      padding: .5rem 1.2rem;
      border-radius: 8px;
      transition: background .2s !important;
    }
    .nav-cta:hover { background: var(--green-dark) !important; }

    .hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
      cursor: pointer;
      background: none;
      border: none;
      padding: 4px;
    }
    .hamburger span {
      display: block;
      width: 24px; height: 2px;
      background: var(--gray-800);
      border-radius: 2px;
      transition: .3s;
    }

    /* ── HERO ── */
    #hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 100px 5% 60px;
      background: linear-gradient(135deg, #f0faf6 0%, #e0f5ec 40%, #f8fafb 100%);
      position: relative;
      overflow: hidden;
    }
    #hero::before {
      content: '';
      position: absolute;
      top: -200px; right: -200px;
      width: 600px; height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(77,184,145,.15) 0%, transparent 70%);
    }
    #hero::after {
      content: '';
      position: absolute;
      bottom: -150px; left: -100px;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(46,139,106,.10) 0%, transparent 70%);
    }

    .hero-inner {
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: var(--green-pale);
      color: var(--green-dark);
      padding: .4rem 1rem;
      border-radius: 100px;
      font-size: .8rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      letter-spacing: .03em;
    }
    .hero-badge::before { content: '●'; font-size: .6rem; color: var(--green-light); }

    .hero-text h1 {
      font-size: clamp(2.2rem, 4vw, 3.4rem);
      font-weight: 800;
      line-height: 1.15;
      color: var(--gray-800);
      margin-bottom: 1.2rem;
    }
    .hero-text h1 span { color: var(--green-mid); }

    .hero-text p {
      font-size: 1.1rem;
      color: var(--gray-600);
      line-height: 1.7;
      margin-bottom: 2rem;
      max-width: 480px;
    }

    .hero-actions {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: var(--green-mid);
      color: var(--white);
      padding: .85rem 1.8rem;
      border-radius: 10px;
      font-weight: 600;
      font-size: .95rem;
      text-decoration: none;
      transition: background .2s, transform .2s, box-shadow .2s;
      box-shadow: 0 4px 14px rgba(46,139,106,.35);
    }
    .btn-primary:hover {
      background: var(--green-dark);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(46,139,106,.4);
    }

    .btn-secondary {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: transparent;
      color: var(--green-dark);
      padding: .85rem 1.8rem;
      border-radius: 10px;
      font-weight: 600;
      font-size: .95rem;
      text-decoration: none;
      border: 2px solid var(--green-light);
      transition: .2s;
    }
    .btn-secondary:hover {
      background: var(--green-pale);
      border-color: var(--green-mid);
    }

    .hero-visual {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .hero-card-wrap {
      position: relative;
      width: 100%;
      max-width: 420px;
    }
    .hero-main-card {
      background: var(--white);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.1);
      text-align: center;
    }
    .hero-main-card img { width: 160px; margin-bottom: 1rem; }
    .hero-main-card h3 { font-weight: 700; color: var(--green-dark); margin-bottom: .4rem; }
    .hero-main-card p { font-size: .85rem; color: var(--gray-600); }

    .floating-badge {
      position: absolute;
      background: var(--white);
      border-radius: 12px;
      padding: .7rem 1rem;
      box-shadow: 0 8px 24px rgba(0,0,0,.1);
      display: flex;
      align-items: center;
      gap: .6rem;
      font-size: .8rem;
      font-weight: 600;
      animation: float 3s ease-in-out infinite;
    }
    .floating-badge .icon { font-size: 1.2rem; }
    .fb-1 { top: -20px; right: -20px; animation-delay: 0s; }
    .fb-2 { bottom: 20px; left: -30px; animation-delay: 1.5s; }

    @keyframes float {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-8px); }
    }

    /* ── STATS ── */
    #stats {
      background: var(--green-dark);
      padding: 3rem 5%;
    }
    .stats-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
      text-align: center;
    }
    .stat-item h3 {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--white);
      margin-bottom: .3rem;
    }
    .stat-item p { font-size: .9rem; color: rgba(255,255,255,.7); }

    /* ── SECTIONS GENERAL ── */
    section { padding: 6rem 5%; }

    .section-label {
      display: inline-block;
      background: var(--green-pale);
      color: var(--green-dark);
      padding: .35rem .9rem;
      border-radius: 6px;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .07em;
      text-transform: uppercase;
      margin-bottom: 1rem;
    }

    .section-title {
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 800;
      color: var(--gray-800);
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    .section-title span { color: var(--green-mid); }

    .section-sub {
      font-size: 1.05rem;
      color: var(--gray-600);
      line-height: 1.7;
      max-width: 560px;
    }

    .section-head { margin-bottom: 3.5rem; }
    .section-head.center { text-align: center; }
    .section-head.center .section-sub { margin: 0 auto; }

    /* ── SERVICIOS ── */
    #servicios { background: var(--gray-50); }

    .services-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }

    .service-card {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid var(--gray-100);
      transition: box-shadow .3s, transform .3s;
      position: relative;
      overflow: hidden;
    }
    .service-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--green-mid), var(--green-light));
      opacity: 0;
      transition: opacity .3s;
    }
    .service-card:hover {
      box-shadow: 0 12px 40px rgba(0,0,0,.08);
      transform: translateY(-4px);
    }
    .service-card:hover::before { opacity: 1; }

    .service-icon {
      width: 54px; height: 54px;
      background: var(--green-pale);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1.2rem;
    }

    .service-card h3 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: .6rem;
      color: var(--gray-800);
    }
    .service-card p { font-size: .9rem; color: var(--gray-600); line-height: 1.6; }

    /* ── PROCESO ── */
    #proceso { background: var(--white); }
    .proceso-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5rem;
      align-items: center;
    }
    .proceso-steps { display: flex; flex-direction: column; gap: 1.5rem; }

    .step {
      display: flex;
      gap: 1.2rem;
      align-items: flex-start;
    }
    .step-num {
      flex-shrink: 0;
      width: 44px; height: 44px;
      border-radius: 50%;
      background: var(--green-pale);
      color: var(--green-dark);
      font-weight: 800;
      font-size: .95rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .step h4 { font-weight: 700; margin-bottom: .3rem; }
    .step p { font-size: .9rem; color: var(--gray-600); line-height: 1.6; }

    .proceso-visual {
      background: linear-gradient(135deg, var(--green-pale), #c8ede0);
      border-radius: 20px;
      padding: 3rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .tech-badge {
      background: var(--white);
      border-radius: 10px;
      padding: .8rem 1.2rem;
      font-size: .88rem;
      font-weight: 600;
      color: var(--green-dark);
      display: flex;
      align-items: center;
      gap: .6rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }

    /* ── PRODUCTOS ── */
    #productos { background: var(--gray-50); }
    .products-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem;
    }
    .product-card {
      background: var(--white);
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid var(--gray-100);
      transition: box-shadow .3s, transform .3s;
    }
    .product-card:hover {
      box-shadow: 0 16px 48px rgba(0,0,0,.1);
      transform: translateY(-4px);
    }
    .product-img {
      height: 180px;
      background: linear-gradient(135deg, var(--green-dark), var(--green-light));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem;
    }
    .product-body { padding: 1.8rem; }
    .product-body h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: .5rem; }
    .product-body p { font-size: .9rem; color: var(--gray-600); line-height: 1.6; margin-bottom: 1.2rem; }
    .product-tags { display: flex; gap: .5rem; flex-wrap: wrap; }
    .tag {
      background: var(--green-pale);
      color: var(--green-dark);
      padding: .25rem .7rem;
      border-radius: 6px;
      font-size: .75rem;
      font-weight: 600;
    }

    /* ── POR QUÉ NOSOTROS ── */
    #porque { background: var(--white); }
    .porque-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5rem;
      align-items: center;
    }
    .porque-visual {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    .pq-card {
      background: var(--gray-50);
      border-radius: 16px;
      padding: 1.5rem;
      border: 1px solid var(--gray-100);
    }
    .pq-card .icon { font-size: 1.8rem; margin-bottom: .7rem; }
    .pq-card h4 { font-weight: 700; margin-bottom: .4rem; font-size: .95rem; }
    .pq-card p { font-size: .82rem; color: var(--gray-600); line-height: 1.5; }
    .pq-card.highlight {
      background: var(--green-dark);
      border-color: var(--green-dark);
      grid-column: span 2;
    }
    .pq-card.highlight h4, .pq-card.highlight p { color: var(--white); }

    /* ── TESTIMONIOS ── */
    #testimonios { background: var(--gray-50); }
    .testimonios-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }
    .testi-card {
      background: var(--white);
      border-radius: 16px;
      padding: 1.8rem;
      border: 1px solid var(--gray-100);
    }
    .stars { color: #f59e0b; margin-bottom: 1rem; font-size: 1rem; }
    .testi-card p { font-size: .9rem; color: var(--gray-600); line-height: 1.7; margin-bottom: 1.2rem; font-style: italic; }
    .testi-author { display: flex; align-items: center; gap: .8rem; }
    .avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--green-light);
      color: var(--white);
      font-weight: 700;
      font-size: .9rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .testi-author h5 { font-size: .9rem; font-weight: 700; }
    .testi-author span { font-size: .78rem; color: var(--gray-400); }

    /* ── CONTACTO ── */
    #contacto {
      background: linear-gradient(135deg, var(--green-dark) 0%, #145240 100%);
      color: var(--white);
    }
    .contacto-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5rem;
      align-items: start;
    }
    .contacto-info h2 { font-size: clamp(1.8rem, 2.5vw, 2.4rem); font-weight: 800; margin-bottom: 1rem; }
    .contacto-info p { color: rgba(255,255,255,.8); line-height: 1.7; margin-bottom: 2rem; }
    .contact-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .contact-item .ci-icon {
      width: 44px; height: 44px;
      background: rgba(255,255,255,.1);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }
    .contact-item span { font-size: .9rem; color: rgba(255,255,255,.85); }

    .contact-form { display: flex; flex-direction: column; gap: 1rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .contact-form input,
    .contact-form textarea,
    .contact-form select {
      width: 100%;
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 10px;
      padding: .85rem 1rem;
      color: var(--white);
      font-size: .9rem;
      font-family: inherit;
      outline: none;
      transition: border-color .2s, background .2s;
    }
    .contact-form input::placeholder,
    .contact-form textarea::placeholder { color: rgba(255,255,255,.5); }
    .contact-form input:focus,
    .contact-form textarea:focus,
    .contact-form select:focus {
      border-color: var(--green-light);
      background: rgba(255,255,255,.15);
    }
    .contact-form select option { background: var(--green-dark); }
    .contact-form textarea { resize: vertical; min-height: 120px; }

    .btn-submit {
      background: var(--white);
      color: var(--green-dark);
      padding: .9rem 2rem;
      border-radius: 10px;
      font-weight: 700;
      font-size: .95rem;
      border: none;
      cursor: pointer;
      transition: background .2s, transform .2s;
      font-family: inherit;
    }
    .btn-submit:hover { background: var(--green-pale); transform: translateY(-2px); }

    /* ── FOOTER ── */
    footer {
      background: #0d3327;
      color: rgba(255,255,255,.6);
      padding: 3rem 5% 2rem;
    }
    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 3rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid rgba(255,255,255,.08);
      margin-bottom: 1.5rem;
    }
    .footer-brand img { height: 45px; margin-bottom: 1rem; }
    .footer-brand p { font-size: .85rem; line-height: 1.6; max-width: 260px; }
    .footer-col h4 { color: var(--white); font-size: .9rem; font-weight: 700; margin-bottom: 1rem; }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: .5rem; }
    .footer-col ul li a {
      color: rgba(255,255,255,.6);
      text-decoration: none;
      font-size: .85rem;
      transition: color .2s;
    }
    .footer-col ul li a:hover { color: var(--green-light); }
    .footer-bottom {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .82rem;
    }

    /* ── MOBILE MENU ── */
    .mobile-nav {
      display: none;
      position: fixed;
      top: 70px; left: 0; right: 0;
      background: var(--white);
      padding: 1.5rem 5%;
      border-top: 1px solid var(--gray-100);
      box-shadow: 0 8px 24px rgba(0,0,0,.08);
      z-index: 99;
      flex-direction: column;
      gap: 1rem;
    }
    .mobile-nav.open { display: flex; }
    .mobile-nav a {
      text-decoration: none;
      font-size: 1rem;
      font-weight: 500;
      color: var(--gray-600);
      padding: .5rem 0;
      border-bottom: 1px solid var(--gray-100);
    }
    .mobile-nav a:last-child { border-bottom: none; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
      .hero-inner { grid-template-columns: 1fr; gap: 3rem; }
      .hero-visual { order: -1; }
      .hero-text h1 { font-size: 2.2rem; }
      .stats-inner { grid-template-columns: repeat(2, 1fr); }
      .services-grid { grid-template-columns: repeat(2, 1fr); }
      .proceso-inner, .porque-inner, .contacto-inner { grid-template-columns: 1fr; gap: 3rem; }
      .products-grid { grid-template-columns: 1fr; }
      .testimonios-grid { grid-template-columns: repeat(2, 1fr); }
      .footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
    }

    @media (max-width: 768px) {
      .nav-links { display: none; }
      .hamburger { display: flex; }
      .stats-inner { grid-template-columns: repeat(2, 1fr); }
      .services-grid { grid-template-columns: 1fr; }
      .testimonios-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .footer-inner { grid-template-columns: 1fr; }
      .footer-bottom { flex-direction: column; gap: .5rem; text-align: center; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
  <a href="#hero" class="nav-logo">
    <img src="{{ asset('images/nuevo_islacontrol.png') }}" alt="IslaControl Logo" />
  </a>
  <ul class="nav-links">
    <li><a href="#servicios">Servicios</a></li>
    <li><a href="#proceso">Proceso</a></li>
    <li><a href="#productos">Productos</a></li>
    <li><a href="#porque">Nosotros</a></li>
    <li><a href="#contacto" class="nav-cta">Contáctanos</a></li>
  </ul>
  <button class="hamburger" id="hamburger" aria-label="Menú">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-nav" id="mobileNav">
  <a href="#servicios" onclick="closeMobile()">Servicios</a>
  <a href="#proceso" onclick="closeMobile()">Proceso</a>
  <a href="#productos" onclick="closeMobile()">Productos</a>
  <a href="#porque" onclick="closeMobile()">Nosotros</a>
  <a href="#contacto" onclick="closeMobile()">Contáctanos</a>
</div>

<!-- HERO -->
<section id="hero">
  <div class="hero-inner">
    <div class="hero-text">
      <div class="hero-badge">Agencia de Software</div>
      <h1>Llevamos tu negocio al <span>siguiente nivel digital</span></h1>
      <p>Desarrollamos software a medida: sistemas de gestión, aplicaciones web y móviles. Soluciones que se adaptan a tu negocio, no al revés.</p>
      <div class="hero-actions">
        <a href="#contacto" class="btn-primary">
          <span>Iniciar proyecto</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="#servicios" class="btn-secondary">Ver servicios</a>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-card-wrap">
        <div class="hero-main-card">
          <img src="{{ asset('images/nuevo_islacontrol.png') }}" alt="IslaControl" />
          <h3>IslaControl Software</h3>
          <p>Tu socio tecnológico de confianza</p>
        </div>
        <div class="floating-badge fb-1">
          <span class="icon">🚀</span>
          <div>
            <div style="color:#1a5c45;font-size:.8rem">Entrega rápida</div>
            <div style="color:#64748b;font-weight:400;font-size:.7rem">Proyectos en tiempo</div>
          </div>
        </div>
        <div class="floating-badge fb-2">
          <span class="icon">⭐</span>
          <div>
            <div style="color:#1a5c45;font-size:.8rem">Alta calidad</div>
            <div style="color:#64748b;font-weight:400;font-size:.7rem">Código limpio</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<div id="stats">
  <div class="stats-inner">
    <div class="stat-item"><h3>50+</h3><p>Proyectos entregados</p></div>
    <div class="stat-item"><h3>30+</h3><p>Clientes satisfechos</p></div>
    <div class="stat-item"><h3>5+</h3><p>Años de experiencia</p></div>
    <div class="stat-item"><h3>100%</h3><p>Soporte post-entrega</p></div>
  </div>
</div>

<!-- SERVICIOS -->
<section id="servicios">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="section-head center">
      <span class="section-label">Servicios</span>
      <h2 class="section-title">Todo lo que tu negocio <span>necesita</span></h2>
      <p class="section-sub">Desde una idea hasta un producto terminado, cubrimos todo el ciclo de desarrollo de software.</p>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">🖥️</div>
        <h3>Sistemas de Gestión</h3>
        <p>Desarrollamos sistemas ERP, POS, inventarios y más, adaptados exactamente a los procesos de tu empresa.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">🌐</div>
        <h3>Aplicaciones Web</h3>
        <p>Plataformas web modernas, rápidas y seguras. Desde portales corporativos hasta complejas apps SaaS.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">📱</div>
        <h3>Apps Móviles</h3>
        <p>Aplicaciones para Android e iOS que conectan a tus clientes con tu negocio en cualquier lugar.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">🗄️</div>
        <h3>Bases de Datos</h3>
        <p>Diseño, optimización y migración de bases de datos. MySQL, PostgreSQL, MongoDB y más.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">🔗</div>
        <h3>APIs & Integraciones</h3>
        <p>Conectamos tus sistemas entre sí o con servicios externos: pagos, mapas, facturación electrónica y más.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">🛡️</div>
        <h3>Mantenimiento & Soporte</h3>
        <p>Nos quedamos contigo después de la entrega. Actualizaciones, correcciones y nuevas funcionalidades.</p>
      </div>
    </div>
  </div>
</section>

<!-- PROCESO -->
<section id="proceso">
  <div class="proceso-inner">
    <div>
      <span class="section-label">Cómo trabajamos</span>
      <h2 class="section-title">Un proceso <span>claro y transparente</span></h2>
      <p class="section-sub" style="margin-bottom:2.5rem">Sabemos que cada proyecto es único. Por eso seguimos un proceso flexible que mantiene al cliente en el centro.</p>
      <div class="proceso-steps">
        <div class="step">
          <div class="step-num">1</div>
          <div><h4>Descubrimiento</h4><p>Entendemos tu negocio, tus objetivos y los problemas que quieres resolver con tecnología.</p></div>
        </div>
        <div class="step">
          <div class="step-num">2</div>
          <div><h4>Propuesta y diseño</h4><p>Creamos una propuesta detallada con alcance, tiempo y costo. Luego diseñamos los wireframes.</p></div>
        </div>
        <div class="step">
          <div class="step-num">3</div>
          <div><h4>Desarrollo ágil</h4><p>Trabajamos en sprints con entregas parciales para que veas el progreso semana a semana.</p></div>
        </div>
        <div class="step">
          <div class="step-num">4</div>
          <div><h4>Entrega y soporte</h4><p>Lanzamos el producto y te capacitamos en su uso. Seguimos contigo después del lanzamiento.</p></div>
        </div>
      </div>
    </div>
    <div class="proceso-visual">
      <h3 style="color:var(--green-dark);font-weight:700;margin-bottom:.5rem">Stack tecnológico</h3>
      <p style="font-size:.85rem;color:var(--gray-600);margin-bottom:1rem">Usamos las mejores herramientas del mercado</p>
      <div class="tech-badge">⚡ Laravel / PHP</div>
      <div class="tech-badge">⚛️ React / Vue.js</div>
      <div class="tech-badge">📱 Flutter / React Native</div>
      <div class="tech-badge">🗄️ MySQL / PostgreSQL</div>
      <div class="tech-badge">☁️ AWS / VPS / cPanel</div>
      <div class="tech-badge">🔒 Seguridad & SSL</div>
      <div class="tech-badge">🎨 Tailwind / Bootstrap</div>
      <div class="tech-badge">🔧 Git / CI-CD</div>
    </div>
  </div>
</section>

<!-- PRODUCTOS -->
<section id="productos">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="section-head center">
      <span class="section-label">Productos</span>
      <h2 class="section-title">Soluciones <span>listas para usar</span></h2>
      <p class="section-sub">Además del desarrollo a medida, contamos con productos propios que puedes licenciar y personalizar.</p>
    </div>
    <div class="products-grid">
      <div class="product-card">
        <div class="product-img">🏪</div>
        <div class="product-body">
          <h3>IslaControl POS</h3>
          <p>Sistema de punto de venta completo con control de inventario, caja, reportes de ventas y gestión de clientes. Ideal para tiendas, restaurantes y negocios de retail.</p>
          <div class="product-tags">
            <span class="tag">Inventario</span><span class="tag">Facturación</span><span class="tag">Reportes</span><span class="tag">Multi-usuario</span>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img">🏢</div>
        <div class="product-body">
          <h3>IslaControl Empresarial</h3>
          <p>Plataforma ERP para pymes que integra ventas, compras, recursos humanos, contabilidad básica y reportería avanzada en un solo sistema.</p>
          <div class="product-tags">
            <span class="tag">ERP</span><span class="tag">RRHH</span><span class="tag">Contabilidad</span><span class="tag">Dashboard</span>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img">🚗</div>
        <div class="product-body">
          <h3>IslaControl Rentas</h3>
          <p>Sistema especializado para negocios de renta de equipos, vehículos o maquinaria. Control de contratos, mantenimiento y disponibilidad en tiempo real.</p>
          <div class="product-tags">
            <span class="tag">Contratos</span><span class="tag">Calendario</span><span class="tag">Clientes</span><span class="tag">Cobros</span>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-img">📦</div>
        <div class="product-body">
          <h3>Software a Medida</h3>
          <p>¿No encuentras lo que buscas? Construimos exactamente lo que tu negocio necesita, desde cero, con tu lógica y tus procesos en mente.</p>
          <div class="product-tags">
            <span class="tag">Personalizado</span><span class="tag">Escalable</span><span class="tag">Tu marca</span><span class="tag">Soporte incluido</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- POR QUÉ NOSOTROS -->
<section id="porque">
  <div class="porque-inner">
    <div>
      <span class="section-label">¿Por qué elegirnos?</span>
      <h2 class="section-title">No somos un proveedor, <span>somos tu aliado</span></h2>
      <p class="section-sub" style="margin-bottom:2rem">En IslaControl creemos que la tecnología debe resolver problemas reales. Por eso escuchamos primero y escribimos código después.</p>
      <a href="#contacto" class="btn-primary">Habla con nosotros</a>
    </div>
    <div class="porque-visual">
      <div class="pq-card">
        <div class="icon">💡</div>
        <h4>Enfoque en resultados</h4>
        <p>No entregamos código, entregamos soluciones que generan valor real para tu negocio.</p>
      </div>
      <div class="pq-card">
        <div class="icon">🤝</div>
        <h4>Comunicación directa</h4>
        <p>Siempre hablas con quien hace el trabajo, sin intermediarios ni malentendidos.</p>
      </div>
      <div class="pq-card">
        <div class="icon">⚡</div>
        <h4>Desarrollo ágil</h4>
        <p>Iteramos rápido y adaptamos el rumbo cuando el negocio lo requiere.</p>
      </div>
      <div class="pq-card">
        <div class="icon">🔒</div>
        <h4>Seguridad primero</h4>
        <p>Buenas prácticas de seguridad en cada línea de código que escribimos.</p>
      </div>
      <div class="pq-card highlight">
        <div class="icon">🌴</div>
        <h4>IslaControl: donde las ideas florecen</h4>
        <p>Como una isla donde todo es posible, convertimos tus ideas en productos de software que marcan la diferencia.</p>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIOS -->
<section id="testimonios">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="section-head center">
      <span class="section-label">Testimonios</span>
      <h2 class="section-title">Lo que dicen <span>nuestros clientes</span></h2>
    </div>
    <div class="testimonios-grid">
      <div class="testi-card">
        <div class="stars">★★★★★</div>
        <p>"IslaControl nos desarrolló el sistema de inventario que necesitábamos. Ahora tenemos todo bajo control y los reportes son increíbles."</p>
        <div class="testi-author">
          <div class="avatar">MR</div>
          <div><h5>Mario Rodríguez</h5><span>Gerente, Ferretería Central</span></div>
        </div>
      </div>
      <div class="testi-card">
        <div class="stars">★★★★★</div>
        <p>"El sistema de punto de venta transformó nuestra operación. Antes tardábamos horas cerrando caja, ahora son minutos."</p>
        <div class="testi-author">
          <div class="avatar">AL</div>
          <div><h5>Ana López</h5><span>Dueña, Boutique Estilo</span></div>
        </div>
      </div>
      <div class="testi-card">
        <div class="stars">★★★★★</div>
        <p>"Muy profesionales. Cumplieron los tiempos pactados y nos dieron seguimiento después de la entrega. Los recomiendo sin dudarlo."</p>
        <div class="testi-author">
          <div class="avatar">JG</div>
          <div><h5>Juan García</h5><span>Director, Rentas Express</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACTO -->
<section id="contacto">
  <div class="contacto-inner">
    <div class="contacto-info">
      <h2>Hagamos algo increíble juntos</h2>
      <p>Cuéntanos tu proyecto. Respondemos en menos de 24 horas con una propuesta inicial sin costo.</p>
      <div class="contact-item">
        <div class="ci-icon">📧</div><span>hola@islacontrol.com</span>
      </div>
      <div class="contact-item">
        <div class="ci-icon">📱</div><span>+52 (555) 123-4567</span>
      </div>
      <div class="contact-item">
        <div class="ci-icon">📍</div><span>México · Atención en toda Latinoamérica</span>
      </div>
    </div>
    <form class="contact-form" onsubmit="handleSubmit(event)">
      <div class="form-row">
        <input type="text" placeholder="Tu nombre" required />
        <input type="email" placeholder="Correo electrónico" required />
      </div>
      <input type="text" placeholder="Nombre de tu empresa" />
      <select>
        <option value="" disabled selected>¿Qué servicio te interesa?</option>
        <option>Sistema de Gestión / ERP</option>
        <option>Punto de Venta (POS)</option>
        <option>Aplicación Web</option>
        <option>App Móvil</option>
        <option>API / Integración</option>
        <option>Mantenimiento y soporte</option>
        <option>Otro</option>
      </select>
      <textarea placeholder="Cuéntanos sobre tu proyecto..." required></textarea>
      <button type="submit" class="btn-submit">Enviar mensaje →</button>
    </form>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <img src="{{ asset('images/islacontrol2.png') }}" alt="IslaControl" />
      <p>Agencia de desarrollo de software especializada en soluciones a medida para empresas de todos los tamaños.</p>
    </div>
    <div class="footer-col">
      <h4>Servicios</h4>
      <ul>
        <li><a href="#servicios">Sistemas ERP</a></li>
        <li><a href="#servicios">Punto de Venta</a></li>
        <li><a href="#servicios">Apps Web</a></li>
        <li><a href="#servicios">Apps Móviles</a></li>
        <li><a href="#servicios">Integraciones</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Empresa</h4>
      <ul>
        <li><a href="#porque">Nosotros</a></li>
        <li><a href="#proceso">Proceso</a></li>
        <li><a href="#productos">Productos</a></li>
        <li><a href="#testimonios">Clientes</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Contacto</h4>
      <ul>
        <li><a href="#contacto">Cotizar proyecto</a></li>
        <li><a href="mailto:hola@islacontrol.com">hola@islacontrol.com</a></li>
        <li><a href="#contacto">Soporte técnico</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 IslaControl. Todos los derechos reservados.</span>
    <span>Hecho con ❤️ en México</span>
  </div>
</footer>

<script>
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });

  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  hamburger.addEventListener('click', () => mobileNav.classList.toggle('open'));
  function closeMobile() { mobileNav.classList.remove('open'); }

  function handleSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('.btn-submit');
    btn.textContent = '✓ Mensaje enviado';
    btn.style.background = 'var(--green-pale)';
    btn.style.color = 'var(--green-dark)';
    btn.disabled = true;
    setTimeout(() => {
      btn.textContent = 'Enviar mensaje →';
      btn.style.background = '';
      btn.style.color = '';
      btn.disabled = false;
      e.target.reset();
    }, 3000);
  }

  // Animate stats
  const animateValue = (el, end, duration) => {
    let start = 0;
    const step = Math.ceil(end / (duration / 16));
    const timer = setInterval(() => {
      start += step;
      if (start >= end) { el.textContent = end + (el.dataset.suffix || ''); clearInterval(timer); }
      else el.textContent = start + (el.dataset.suffix || '');
    }, 16);
  };
  const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.querySelectorAll('h3').forEach(h3 => {
          const text = h3.textContent;
          const num = parseInt(text);
          h3.dataset.suffix = text.replace(num, '');
          animateValue(h3, num, 1000);
        });
        statsObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  const statsSection = document.getElementById('stats');
  if (statsSection) statsObserver.observe(statsSection);

  // Fade-in on scroll
  const fadeEls = document.querySelectorAll('.service-card, .step, .pq-card, .testi-card, .product-card');
  const fadeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, 80);
        fadeObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  fadeEls.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity .5s ease, transform .5s ease';
    fadeObserver.observe(el);
  });
</script>
</body>
</html>
