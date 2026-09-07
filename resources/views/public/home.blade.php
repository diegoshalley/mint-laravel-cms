@extends('layouts.public')

@section('title', 'Home')
@section('meta_description', 'Official Ministry of the Interior Ghana portal for public safety information, services, agencies, notices and documents.')

@section('content')
<section class="hero">
    <div class="hero__texture" aria-hidden="true"></div>
    <div class="container hero__grid">
        <div class="hero__content">
            <p class="eyebrow eyebrow--light">Republic of Ghana • Ministry of the Interior</p>
            <h1>Serving Ghana through <span>peace, security</span> and public safety.</h1>
            <p class="hero__lead">Find trusted information, access essential services and connect with the agencies working to keep our communities safe.</p>
            <div class="hero__actions">
                <a class="button button--gold" href="{{ route('services.index') }}">Explore public services <span aria-hidden="true">→</span></a>
                <a class="button button--ghost" href="#about">About the Ministry</a>
            </div>
            <div class="hero__trust">
                <span><b>11</b> agencies</span>
                <span><b>24/7</b> emergency support</span>
                <span><b>1</b> trusted portal</span>
            </div>
        </div>

        <aside class="hero-service-card" aria-label="Quick service finder">
            <p class="eyebrow">Start here</p>
            <h2>How can we help?</h2>
            <p>Choose a common task or browse all Ministry services.</p>
            <div class="quick-tasks">
                <a href="{{ route('services.index') }}#immigration"><span class="quick-tasks__icon">01</span><span><strong>Passport & immigration</strong><small>Travel, entry and residency</small></span><b>→</b></a>
                <a href="{{ route('services.index') }}#citizenship"><span class="quick-tasks__icon">02</span><span><strong>Citizenship services</strong><small>Applications and guidance</small></span><b>→</b></a>
                <a href="{{ route('services.index') }}#permits"><span class="quick-tasks__icon">03</span><span><strong>Permits & licences</strong><small>Public safety permissions</small></span><b>→</b></a>
            </div>
            <a class="text-link" href="{{ route('services.index') }}">View all services <span aria-hidden="true">→</span></a>
        </aside>
    </div>
</section>

<section class="alert-band" aria-label="Public information">
    <div class="container alert-band__inner">
        <span class="alert-band__label">Public notice</span>
        <p>Use only official government channels when accessing Ministry services or making payments.</p>
        <a href="#news">View notices →</a>
    </div>
</section>

<section class="section section--services" id="services">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div><p class="eyebrow">Public services</p><h2>Government services, made easier to find.</h2></div>
            <p>Start with what you need to do—not with the government department you need to know.</p>
        </div>
        <div class="service-grid">
            <a class="service-card" href="{{ route('services.index') }}#immigration"><span class="service-card__number">01</span><h3>Immigration & travel</h3><p>Find guidance for entry, residence, permits and related immigration services.</p><span class="service-card__link">Explore services →</span></a>
            <a class="service-card" href="{{ route('services.index') }}#citizenship"><span class="service-card__number">02</span><h3>Citizenship</h3><p>Understand citizenship processes, requirements and where to begin an application.</p><span class="service-card__link">Explore services →</span></a>
            <a class="service-card" href="{{ route('services.index') }}#safety"><span class="service-card__number">03</span><h3>Safety & security</h3><p>Connect to public safety information, emergency resources and responsible agencies.</p><span class="service-card__link">Explore services →</span></a>
            <a class="service-card service-card--accent" href="{{ route('services.index') }}"><span class="service-card__number">04</span><h3>All Ministry services</h3><p>Browse the complete service directory by topic, agency or public need.</p><span class="service-card__link">Browse directory →</span></a>
        </div>
    </div>
</section>

<section class="section mandate" id="about">
    <div class="container mandate__grid">
        <div class="mandate__visual" aria-hidden="true">
            <div class="mandate__flag"><span></span><span></span><span></span></div>
            <div class="mandate__seal">Ghana<br><b>Interior</b></div>
            <p>Peace • Security • Public Safety</p>
        </div>
        <div class="mandate__content">
            <p class="eyebrow">Our mandate</p>
            <h2>Building a safe, secure and peaceful Ghana.</h2>
            <p class="lead-copy">The Ministry provides policy direction and coordination for internal security and works with its agencies to protect life, property and national stability.</p>
            <div class="mandate__points">
                <div><strong>01</strong><span><b>Policy leadership</b><small>Coordinating national internal security policy and priorities.</small></span></div>
                <div><strong>02</strong><span><b>Agency oversight</b><small>Supporting accountable, effective public safety institutions.</small></span></div>
                <div><strong>03</strong><span><b>Citizen service</b><small>Making public-facing services clearer and easier to access.</small></span></div>
            </div>
            <a class="text-link" href="#">Learn about the Ministry →</a>
        </div>
    </div>
</section>

<section class="section section--agencies" id="agencies">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div><p class="eyebrow">Our agencies</p><h2>One Ministry. A network serving Ghana.</h2></div>
            <p>Connect quickly with the institutions responsible for policing, fire safety, immigration, prisons and other Interior functions.</p>
        </div>
        <div class="agency-grid">
            <article><span>GPS</span><h3>Ghana Police Service</h3><p>Law enforcement and public safety.</p></article>
            <article><span>GIS</span><h3>Ghana Immigration Service</h3><p>Migration and border management.</p></article>
            <article><span>GNFS</span><h3>Ghana National Fire Service</h3><p>Fire prevention and emergency response.</p></article>
            <article><span>GPS</span><h3>Ghana Prisons Service</h3><p>Safe custody, rehabilitation and reintegration.</p></article>
        </div>
        <div class="center-action"><a class="button button--dark" href="#">View all agencies →</a></div>
    </div>
</section>

<section class="section news-section" id="news">
    <div class="container">
        <div class="section-heading section-heading--split"><div><p class="eyebrow">Official updates</p><h2>News, notices & public information.</h2></div><a class="text-link" href="#">View all updates →</a></div>
        <div class="news-grid">
            <article class="news-card news-card--feature"><div class="news-card__image news-card__image--one"><span>Ministry update</span></div><div class="news-card__body"><p class="meta">Official notice</p><h3>Stay informed through verified Ministry channels</h3><p>Public notices, announcements and service updates will be published here as authoritative information.</p><a href="#">Read update →</a></div></article>
            <article class="news-card"><div class="news-card__image news-card__image--two"><span>Public safety</span></div><div class="news-card__body"><p class="meta">Public information</p><h3>Know the right emergency number before you need it</h3><p>Keep Ghana's emergency contacts accessible and share them responsibly.</p><a href="#emergency">View contacts →</a></div></article>
            <article class="news-card"><div class="news-card__image news-card__image--three"><span>Services</span></div><div class="news-card__body"><p class="meta">Digital services</p><h3>Use the service directory to find the correct starting point</h3><p>We are organizing services around citizen needs, requirements and responsible agencies.</p><a href="{{ route('services.index') }}">Find a service →</a></div></article>
        </div>
    </div>
</section>

<section class="emergency" id="emergency">
    <div class="container emergency__inner">
        <div><p class="eyebrow eyebrow--light">Need urgent help?</p><h2>Emergency services are available 24/7.</h2></div>
        <div class="emergency__numbers"><a href="tel:112"><strong>112</strong><span>Emergency</span></a><a href="tel:191"><strong>191</strong><span>Police</span></a><a href="tel:192"><strong>192</strong><span>Fire</span></a><a href="tel:193"><strong>193</strong><span>Ambulance</span></a></div>
    </div>
</section>
@endsection
