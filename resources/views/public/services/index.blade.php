@extends('layouts.public')

@section('title', 'Services')
@section('meta_description', 'Find Ministry of the Interior Ghana public services, requirements, responsible agencies and official service channels.')

@section('content')
<section class="page-hero">
    <div class="container page-hero__grid">
        <div><p class="eyebrow eyebrow--light">Public service directory</p><h1>Find the service you need.</h1><p>Browse services by what you want to do. Each service will guide you to requirements, responsible agencies and official channels.</p></div>
        <div class="page-hero__search">
            <label for="service-search">Search Ministry services</label>
            <div class="search-box"><span aria-hidden="true">⌕</span><input id="service-search" type="search" placeholder="e.g. citizenship, permit, immigration" data-service-search><button type="button">Search</button></div>
            <p>Popular: <a href="#immigration">Immigration</a> · <a href="#citizenship">Citizenship</a> · <a href="#permits">Permits</a></p>
        </div>
    </div>
</section>

<section class="service-nav" aria-label="Service categories">
    <div class="container service-nav__inner"><a href="#immigration">Immigration & travel</a><a href="#citizenship">Citizenship</a><a href="#permits">Permits & licences</a><a href="#safety">Safety & security</a><a href="#other">Other services</a></div>
</section>

<section class="section service-directory">
    <div class="container">
        <div class="directory-intro"><div><p class="eyebrow">Browse services</p><h2>Start with your task.</h2></div><p>We deliberately avoid forcing citizens to understand the Ministry's organizational structure before they can find help.</p></div>

        <div class="service-category" id="immigration" data-service-group>
            <div class="service-category__heading"><span>01</span><div><h2>Immigration & travel</h2><p>Entry, residence, work and migration-related guidance.</p></div></div>
            <div class="directory-grid">
                <article class="directory-card" data-service-card><p class="tag">Immigration</p><h3>Entry & residence guidance</h3><p>Find the correct official channel for entry, residence and immigration enquiries.</p><div class="directory-card__meta"><span>Responsible agency</span><b>Ghana Immigration Service</b></div><a href="#">View service details →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Immigration</p><h3>Work & residence permits</h3><p>Understand the starting point for immigration permits and the documents you may need.</p><div class="directory-card__meta"><span>Responsible agency</span><b>Ghana Immigration Service</b></div><a href="#">View service details →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Travel</p><h3>Border & travel enquiries</h3><p>Access official information for migration, border processes and related enquiries.</p><div class="directory-card__meta"><span>Responsible agency</span><b>Ghana Immigration Service</b></div><a href="#">View service details →</a></article>
            </div>
        </div>

        <div class="service-category" id="citizenship" data-service-group>
            <div class="service-category__heading"><span>02</span><div><h2>Citizenship</h2><p>Citizenship applications, registration and supporting guidance.</p></div></div>
            <div class="directory-grid">
                <article class="directory-card" data-service-card><p class="tag">Citizenship</p><h3>Citizenship application guidance</h3><p>Learn where to start, what information is required and how the official process works.</p><div class="directory-card__meta"><span>Service owner</span><b>Ministry of the Interior</b></div><a href="#">View service details →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Citizenship</p><h3>Citizenship status enquiries</h3><p>Find the appropriate contact route for questions about an existing citizenship matter.</p><div class="directory-card__meta"><span>Service owner</span><b>Ministry of the Interior</b></div><a href="#">View service details →</a></article>
            </div>
        </div>

        <div class="service-category" id="permits" data-service-group>
            <div class="service-category__heading"><span>03</span><div><h2>Permits & licences</h2><p>Public safety permissions and regulatory services.</p></div></div>
            <div class="directory-grid">
                <article class="directory-card" data-service-card><p class="tag">Permits</p><h3>Public safety permits</h3><p>Find guidance on permissions administered through the Ministry and its agencies.</p><div class="directory-card__meta"><span>Service owner</span><b>Ministry / responsible agency</b></div><a href="#">View service details →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Licensing</p><h3>Licence & approval enquiries</h3><p>Identify the responsible institution and official route before submitting an application.</p><div class="directory-card__meta"><span>Channel</span><b>Official government service</b></div><a href="#">View service details →</a></article>
            </div>
        </div>

        <div class="service-category" id="safety" data-service-group>
            <div class="service-category__heading"><span>04</span><div><h2>Safety & security</h2><p>Emergency, policing, fire and public safety information.</p></div></div>
            <div class="directory-grid">
                <article class="directory-card" data-service-card><p class="tag">Emergency</p><h3>Emergency contacts</h3><p>Call the appropriate emergency service when there is an immediate threat to life or property.</p><div class="directory-card__meta"><span>National emergency</span><b>112</b></div><a href="tel:112">Call 112 →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Police</p><h3>Police services</h3><p>Connect with Ghana Police Service information and public-facing support channels.</p><div class="directory-card__meta"><span>Responsible agency</span><b>Ghana Police Service</b></div><a href="#">View service details →</a></article>
                <article class="directory-card" data-service-card><p class="tag">Fire safety</p><h3>Fire & rescue services</h3><p>Find fire prevention, emergency response and related public safety information.</p><div class="directory-card__meta"><span>Responsible agency</span><b>Ghana National Fire Service</b></div><a href="#">View service details →</a></article>
            </div>
        </div>

        <div class="service-category" id="other" data-service-group>
            <div class="service-category__heading"><span>05</span><div><h2>Other Interior services</h2><p>Additional services will be added as Stage 1 content is completed.</p></div></div>
            <div class="directory-grid">
                <article class="directory-card" data-service-card><p class="tag">Enquiries</p><h3>General Ministry enquiry</h3><p>Not sure which service applies? Contact the Ministry and we will direct your enquiry appropriately.</p><div class="directory-card__meta"><span>Email</span><b>info@mint.gov.gh</b></div><a href="mailto:info@mint.gov.gh">Contact the Ministry →</a></article>
            </div>
        </div>

        <div class="no-results" data-no-results hidden><h2>No matching service found.</h2><p>Try a broader term or contact the Ministry for help finding the correct service.</p></div>
    </div>
</section>

<section class="service-help">
    <div class="container service-help__inner"><div><p class="eyebrow eyebrow--light">Need help?</p><h2>Can't find the right service?</h2><p>Contact the Ministry and we will help identify the appropriate agency or service channel.</p></div><a class="button button--gold" href="mailto:info@mint.gov.gh">Contact the Ministry →</a></div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.querySelector('[data-service-search]');
    const cards = [...document.querySelectorAll('[data-service-card]')];
    const groups = [...document.querySelectorAll('[data-service-group]')];
    const empty = document.querySelector('[data-no-results]');
    if (!input) return;
    input.addEventListener('input', () => {
        const query = input.value.trim().toLowerCase();
        let visible = 0;
        cards.forEach(card => {
            const show = !query || card.textContent.toLowerCase().includes(query);
            card.hidden = !show;
            if (show) visible++;
        });
        groups.forEach(group => {
            group.hidden = ![...group.querySelectorAll('[data-service-card]')].some(card => !card.hidden);
        });
        empty.hidden = visible !== 0;
    });
})();
</script>
@endpush
