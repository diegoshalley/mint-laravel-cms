<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b3b2e">
    <title>@yield('title', 'Ministry of the Interior') | Republic of Ghana</title>
    <meta name="description" content="@yield('meta_description', 'Official website of the Ministry of the Interior, Republic of Ghana.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('head')
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

<div class="gov-strip" aria-label="Republic of Ghana identity">
    <div class="container gov-strip__inner">
        <div class="gov-mark" aria-hidden="true"><span></span><span></span><span></span></div>
        <p>Official website of the Ministry of the Interior, Republic of Ghana</p>
        <div class="gov-strip__links">
            <a href="tel:112">Emergency 112</a>
            <a href="mailto:info@mint.gov.gh">info@mint.gov.gh</a>
        </div>
    </div>
</div>

<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Ministry of the Interior home">
            <span class="brand__crest" aria-hidden="true">GH</span>
            <span class="brand__text">
                <strong>Ministry of the Interior</strong>
                <small>Peace • Security • Public Safety</small>
            </span>
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
            <span></span><span></span><span></span><span class="sr-only">Toggle navigation</span>
        </button>

        <nav class="primary-nav" id="primary-navigation" aria-label="Primary navigation" data-primary-nav>
            <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Home</a>
            <a href="#about">About</a>
            <a class="{{ request()->routeIs('services.*') ? 'is-active' : '' }}" href="{{ route('services.index') }}">Services</a>
            <a href="#agencies">Agencies</a>
            <a href="#news">News & Notices</a>
            <a href="#documents">Documents</a>
            <a href="#contact">Contact</a>
        </nav>

        <a class="header-action" href="{{ route('services.index') }}">Access a service</a>
    </div>
</header>

<main id="main-content">
    @yield('content')
</main>

<footer class="site-footer" id="contact">
    <div class="container site-footer__grid">
        <div>
            <div class="brand brand--footer">
                <span class="brand__crest" aria-hidden="true">GH</span>
                <span class="brand__text"><strong>Ministry of the Interior</strong><small>Republic of Ghana</small></span>
            </div>
            <p class="footer-intro">Providing leadership for internal security, public safety, migration and related services in Ghana.</p>
        </div>
        <div>
            <h2>Quick links</h2>
            <a href="{{ route('services.index') }}">Public services</a>
            <a href="#news">News & notices</a>
            <a href="#documents">Documents</a>
            <a href="#agencies">Agencies</a>
        </div>
        <div>
            <h2>Contact</h2>
            <p>P.O. Box M42, Accra, Ghana</p>
            <p>Digital Address: GA-111-5377</p>
            <a href="mailto:info@mint.gov.gh">info@mint.gov.gh</a>
            <a href="tel:+233302684421">+233 302 684 421</a>
        </div>
        <div>
            <h2>Emergency</h2>
            <a href="tel:112"><strong>112</strong> Emergency Response</a>
            <a href="tel:191"><strong>191</strong> Police</a>
            <a href="tel:192"><strong>192</strong> Fire Service</a>
            <a href="tel:193"><strong>193</strong> Ambulance</a>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <p>&copy; {{ date('Y') }} Ministry of the Interior, Republic of Ghana.</p>
        <p>Built for accessible, secure public service delivery.</p>
    </div>
</footer>

<script>
(() => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('[data-primary-nav]');
    if (!toggle || !nav) return;
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        nav.classList.toggle('is-open', !open);
    });
})();
</script>
@stack('scripts')
</body>
</html>
