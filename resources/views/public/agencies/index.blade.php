@extends('layouts.public')

@section('title', 'Agencies')
@section('meta_description', 'Explore the ten agencies under Ghana’s Ministry of the Interior and find the institution responsible for policing, fire safety, immigration, prisons, disaster management and related functions.')

@push('head')
<style>
.agencies-hero{background:linear-gradient(135deg,#0b3b2e 0%,#125340 72%,#173c31 100%);color:#fff;padding:78px 0}.agencies-hero__grid{display:grid;grid-template-columns:1.05fr .95fr;gap:80px;align-items:center}.agencies-hero h1{font:800 clamp(42px,5vw,62px)/1.03 Manrope,sans-serif;letter-spacing:-.04em;margin:0}.agencies-hero p:not(.eyebrow){color:#c7dbd2;font-size:17px;max-width:650px}.agencies-hero__panel{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:30px}.agencies-hero__panel strong{display:block;font:800 44px/1 Manrope,sans-serif;color:var(--gold)}.agencies-hero__panel span{display:block;margin-top:10px;font-size:13px;color:#d4e3dd}.agency-directory{background:var(--cream)}.agency-directory__intro{display:grid;grid-template-columns:1.2fr .8fr;gap:70px;align-items:end;margin-bottom:38px}.agency-directory__intro h2{font:800 clamp(30px,4vw,46px)/1.12 Manrope,sans-serif;letter-spacing:-.035em;margin:0}.agency-directory__intro>p{color:var(--muted);margin:0}.agency-directory-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}.agency-directory-card{background:#fff;border:1px solid var(--line);border-radius:15px;padding:26px;display:grid;grid-template-columns:58px 1fr;gap:18px;align-items:start}.agency-directory-card__mark{width:58px;height:58px;border-radius:13px;background:var(--green-3);color:var(--green);display:grid;place-items:center;text-align:center;font:800 10px/1.1 Manrope,sans-serif}.agency-directory-card h3{font:800 20px/1.25 Manrope,sans-serif;margin:0 0 7px}.agency-directory-card p{margin:0;color:var(--muted);font-size:13px}.agency-directory-card a{display:inline-block;margin-top:14px;color:var(--green);font-size:13px;font-weight:800}.agency-help{display:grid;grid-template-columns:.85fr 1.15fr;gap:70px;align-items:center}.agency-help__copy h2{font:800 clamp(30px,4vw,46px)/1.12 Manrope,sans-serif;letter-spacing:-.035em;margin:0}.agency-help__copy p{color:var(--muted)}.agency-help__list{border-top:1px solid var(--line)}.agency-help__list div{display:grid;grid-template-columns:140px 1fr;gap:25px;padding:17px 0;border-bottom:1px solid var(--line)}.agency-help__list strong{color:var(--green);font:800 13px Manrope,sans-serif}.agency-help__list span{font-size:13px;color:var(--muted)}@media(max-width:900px){.agencies-hero__grid,.agency-directory__intro,.agency-help{grid-template-columns:1fr;gap:35px}}@media(max-width:720px){.agency-directory-grid{grid-template-columns:1fr}.agency-directory-card{grid-template-columns:48px 1fr}.agency-directory-card__mark{width:48px;height:48px}.agency-help__list div{grid-template-columns:1fr;gap:4px}}
</style>
@endpush

@section('content')
<section class="agencies-hero">
    <div class="container agencies-hero__grid">
        <div>
            <p class="eyebrow eyebrow--light">Agencies</p>
            <h1>A network of institutions serving Ghana.</h1>
            <p>The Ministry works through ten agencies responsible for law enforcement, corrections, fire safety, immigration, disaster management, peacebuilding and other national functions.</p>
        </div>
        <div class="agencies-hero__panel">
            <strong>10</strong>
            <span>Agencies currently listed under the Ministry of the Interior.</span>
        </div>
    </div>
</section>

<section class="section agency-directory">
    <div class="container">
        <div class="agency-directory__intro">
            <div><p class="eyebrow">Agency directory</p><h2>Find the institution responsible for your need.</h2></div>
            <p>Each agency has a distinct mandate. Use this directory to identify the right institution before starting an enquiry or service request.</p>
        </div>

        <div class="agency-directory-grid">
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GPS</span><div><h3>Ghana Police Service</h3><p>Law enforcement, crime prevention, public order and protection of life and property.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GPrS</span><div><h3>Ghana Prisons Service</h3><p>Safe custody, rehabilitation and reintegration of persons committed to prison custody.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GNFS</span><div><h3>Ghana National Fire Service</h3><p>Fire prevention, fire safety education, rescue and emergency response.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GIS</span><div><h3>Ghana Immigration Service</h3><p>Migration management, border control and enforcement of immigration laws.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">NACOC</span><div><h3>Narcotics Control Commission</h3><p>Control, prevention and enforcement relating to narcotic drugs and illicit substances.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">NADMO</span><div><h3>National Disaster Management Organization</h3><p>Disaster preparedness, risk reduction, coordination and emergency relief.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GCG</span><div><h3>Gaming Commission of Ghana</h3><p>Regulation, supervision and control of gaming activities in Ghana.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">NACSA</span><div><h3>National Commission on Small Arms and Light Weapons</h3><p>Coordination of national efforts to control illicit small arms and light weapons.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">NPC</span><div><h3>National Peace Council</h3><p>Conflict prevention, mediation, peacebuilding and promotion of national cohesion.</p><a href="#">Agency profile →</a></div></article>
            <article class="agency-directory-card"><span class="agency-directory-card__mark">GRB</span><div><h3>Ghana Refugee Board</h3><p>Protection, management and coordination of refugee matters in Ghana.</p><a href="#">Agency profile →</a></div></article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container agency-help">
        <div class="agency-help__copy"><p class="eyebrow">Not sure where to go?</p><h2>Start with the public service directory.</h2><p>If you know the task but not the responsible agency, use the Services page first. It is deliberately organised around citizen needs rather than government structure.</p><a class="button button--dark" href="{{ route('services.index') }}">Browse services →</a></div>
        <div class="agency-help__list"><div><strong>Emergency</strong><span>Call 112 for the national Emergency Response Centre.</span></div><div><strong>Police</strong><span>Call 191 for Ghana Police Service emergency response.</span></div><div><strong>Fire</strong><span>Call 192 for Ghana National Fire Service emergency response.</span></div><div><strong>Ambulance</strong><span>Call 193 for ambulance services.</span></div></div>
    </div>
</section>
@endsection
