@extends('layouts.public')

@section('title', 'News & Notices')
@section('meta_description', 'Official news, press releases, notices and public safety information from Ghana’s Ministry of the Interior.')

@push('head')
<style>
.publications-hero{background:linear-gradient(135deg,#0b3b2e 0%,#125340 72%,#173c31 100%);color:#fff;padding:78px 0}.publications-hero__grid{display:grid;grid-template-columns:1.1fr .9fr;gap:80px;align-items:center}.publications-hero h1{font:800 clamp(42px,5vw,62px)/1.03 Manrope,sans-serif;letter-spacing:-.04em;margin:0}.publications-hero p:not(.eyebrow){color:#c7dbd2;font-size:17px}.publications-hero__panel{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:30px}.publications-hero__panel strong{font:800 22px/1.25 Manrope,sans-serif;display:block}.publications-hero__panel span{display:block;color:#b9d4c8;font-size:12px;margin-top:8px}.publication-tabs{border-bottom:1px solid var(--line);background:#fff;position:sticky;top:84px;z-index:25}.publication-tabs__inner{display:flex;gap:28px;overflow:auto}.publication-tabs a{white-space:nowrap;padding:17px 0;font-size:13px;font-weight:700;color:var(--muted)}.publication-feature{display:grid;grid-template-columns:1.05fr .95fr;border:1px solid var(--line);border-radius:18px;overflow:hidden;background:#fff}.publication-feature__visual{min-height:390px;background:linear-gradient(135deg,#102e26,#22684f);padding:30px;display:flex;align-items:end;color:#fff}.publication-feature__visual span{font-size:11px;text-transform:uppercase;letter-spacing:.12em;background:rgba(0,0,0,.28);padding:6px 9px;border-radius:5px}.publication-feature__body{padding:36px;display:flex;flex-direction:column;justify-content:center}.publication-feature__body .meta,.publication-card .meta{color:var(--green);font-size:10px;text-transform:uppercase;letter-spacing:.1em;font-weight:800}.publication-feature__body h2{font:800 clamp(28px,3vw,40px)/1.12 Manrope,sans-serif;letter-spacing:-.03em;margin:9px 0 13px}.publication-feature__body p{color:var(--muted)}.publication-feature__body a,.publication-card a{color:var(--green);font-size:13px;font-weight:800}.publication-section{background:var(--cream)}.publication-list-heading{display:flex;justify-content:space-between;gap:30px;align-items:end;margin-bottom:28px}.publication-list-heading h2{font:800 clamp(28px,3vw,40px)/1.12 Manrope,sans-serif;letter-spacing:-.03em;margin:0}.publication-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.publication-card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:25px;display:flex;flex-direction:column;min-height:250px}.publication-card h3{font:800 19px/1.3 Manrope,sans-serif;margin:10px 0}.publication-card p:not(.meta){color:var(--muted);font-size:13px}.publication-card a{margin-top:auto}.notice-list{border-top:1px solid var(--line)}.notice-row{display:grid;grid-template-columns:130px 1fr auto;gap:28px;align-items:center;padding:20px 0;border-bottom:1px solid var(--line)}.notice-row time{font-size:12px;color:var(--muted)}.notice-row h3{font:800 16px/1.3 Manrope,sans-serif;margin:0}.notice-row a{font-size:13px;font-weight:800;color:var(--green)}@media(max-width:900px){.publications-hero__grid,.publication-feature{grid-template-columns:1fr}.publication-feature__visual{min-height:260px}.publication-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:720px){.publication-tabs{top:72px}.publication-grid{grid-template-columns:1fr}.notice-row{grid-template-columns:1fr;gap:7px}.publication-list-heading{display:block}}
</style>
@endpush

@section('content')
<section class="publications-hero">
    <div class="container publications-hero__grid">
        <div><p class="eyebrow eyebrow--light">News & Notices</p><h1>Official information from the Ministry.</h1><p>Read verified Ministry news, press releases, public safety notices and service announcements from one authoritative source.</p></div>
        <div class="publications-hero__panel"><strong>Trust the source.</strong><span>For sensitive public safety information, confirm announcements through official Ministry channels before sharing them.</span></div>
    </div>
</section>

<nav class="publication-tabs" aria-label="Publication categories"><div class="container publication-tabs__inner"><a href="#latest">Latest news</a><a href="#press-releases">Press releases</a><a href="#notices">Public notices</a></div></nav>

<section class="section" id="latest">
    <div class="container">
        <article class="publication-feature">
            <div class="publication-feature__visual"><span>Latest News • 4 September 2026</span></div>
            <div class="publication-feature__body"><p class="meta">Latest news</p><h2>Interior Minister directs Immigration Commanders to crack down on illegal immigrants over rising street begging</h2><p>The Ministry's latest published news item focuses on immigration enforcement and regional command responsibility.</p><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read on official Ministry site →</a></div>
        </article>
    </div>
</section>

<section class="section publication-section">
    <div class="container">
        <div class="publication-list-heading"><div><p class="eyebrow">Recent updates</p><h2>Latest Ministry news.</h2></div></div>
        <div class="publication-grid">
            <article class="publication-card"><p class="meta">21 Aug 2026 • Latest News</p><h3>Police Regional Commanders’ Conference: Interior Minister charges commanders to strengthen professionalism and accountability</h3><p>Official Ministry coverage of the Regional Commanders’ Conference.</p><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read update →</a></article>
            <article class="publication-card"><p class="meta">19 Aug 2026 • Latest News</p><h3>Interior Ministry holds 3-day consultation to finalize Community Service implementation plan</h3><p>Stakeholder consultation on implementation planning and coordination.</p><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read update →</a></article>
            <article class="publication-card"><p class="meta">18 Aug 2026 • Latest News</p><h3>Deputy Interior Minister calls for strict compliance with armoured bullion vehicles guideline</h3><p>A public safety and compliance update from Ministry leadership.</p><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read update →</a></article>
        </div>
    </div>
</section>

<section class="section" id="press-releases">
    <div class="container">
        <div class="publication-list-heading"><div><p class="eyebrow">Press releases</p><h2>Official statements & public safety announcements.</h2></div></div>
        <div class="notice-list">
            <article class="notice-row"><time datetime="2026-08-24">24 Aug 2026</time><h3>Review of Restriction on Motorbike Riding in the Bawku Municipality and its Environs in the Upper East Region</h3><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read →</a></article>
            <article class="notice-row"><time datetime="2026-08-13">13 Aug 2026</time><h3>Interior Ministry Condemns Assault in Police Custody</h3><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read →</a></article>
            <article class="notice-row"><time datetime="2026-07-29">29 Jul 2026</time><h3>Update On Curfew in the Bawku Municipality and its Environs, Upper East Region</h3><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read →</a></article>
            <article class="notice-row"><time datetime="2026-07-13">13 Jul 2026</time><h3>Imposition Of Curfew On Nkwanta South Municipality In The Oti Region</h3><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read →</a></article>
            <article class="notice-row"><time datetime="2026-06-29">29 Jun 2026</time><h3>Public Safety Advisory on Flooding</h3><a href="https://www.mint.gov.gh/" target="_blank" rel="noopener">Read →</a></article>
        </div>
    </div>
</section>

<section class="section publication-section" id="notices">
    <div class="container"><div class="publication-list-heading"><div><p class="eyebrow">Public notices</p><h2>Important information citizens should not miss.</h2></div></div><div class="publication-grid"><article class="publication-card"><p class="meta">Public safety</p><h3>Emergency numbers</h3><p>Emergency Response Centre 112, Police 191, Fire 192 and Ambulance 193.</p><a href="tel:112">Call 112 →</a></article><article class="publication-card"><p class="meta">Official channels</p><h3>Verify Ministry announcements before acting</h3><p>Use official Ministry channels for notices, applications, permits and payments.</p><a href="{{ route('contact') }}">Contact the Ministry →</a></article><article class="publication-card"><p class="meta">Digital services</p><h3>Start service requests from the public service directory</h3><p>Find the correct service, responsible unit and official starting point.</p><a href="{{ route('services.index') }}">Browse services →</a></article></div></div>
</section>
@endsection
