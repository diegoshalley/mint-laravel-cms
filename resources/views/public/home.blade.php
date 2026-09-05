@extends('layouts.public')
@section('title', 'Ministry of the Interior — Republic of Ghana')
@section('content')
<section class="notice"><div class="container"><strong>Official information:</strong> Verify Ministry announcements and services on this website before making payments.</div></section>
<section class="hero"><div class="container hero-grid"><div>
    <p class="eyebrow">Safety · Security · Stability</p><h1>Interior services, information and public safety resources</h1>
    <p class="lead">Find the right government service, official notice or agency without navigating the Ministry’s internal structure.</p>
    <form class="service-search" action="{{ route('services.index') }}"><label for="service">How can we help?</label><div><input id="service" name="q" placeholder="Try passport, residence permit or fire certificate"><button>Find a service</button></div></form>
</div><aside class="quick-card"><h2>Popular services</h2><a href="/services">Passport services <span>→</span></a><a href="/services">Immigration and residence <span>→</span></a><a href="/services">Fire safety and certification <span>→</span></a><a href="/services">Police and public safety <span>→</span></a></aside></div></section>
<section class="section"><div class="container"><div class="section-heading"><div><p class="eyebrow">Official updates</p><h2>Latest notices and releases</h2></div><a href="{{ route('publications.index') }}">View all updates →</a></div>
<div class="cards">@forelse($latest as $item)<article class="card"><p class="meta">{{ $item->published_at?->format('j M Y') }}</p><h3>{{ $item->title }}</h3><p>{{ $item->summary }}</p><a href="/news-notices/{{ $item->slug }}">Read official update</a></article>@empty
<article class="card"><p class="meta">Public notice</p><h3>Official Ministry notices will appear here</h3><p>Approved releases are published through the Ministry’s controlled editorial workflow.</p></article>
<article class="card"><p class="meta">Public services</p><h3>Use verified service channels</h3><p>Check requirements, fees and responsible agencies before beginning an application.</p></article>
<article class="card"><p class="meta">Safety information</p><h3>Emergency contacts remain easy to reach</h3><p>Police, fire and ambulance numbers are available on every page.</p></article>@endforelse</div></div></section>
<section id="about" class="section dark"><div class="container split"><div><p class="eyebrow gold">Our mandate</p><h2>Keeping Ghana safe, secure and stable</h2><p>The Ministry exercises oversight of internal security, migration management, crime prevention, disaster readiness and related public services through its agencies.</p></div><div><h3>Find an agency</h3><p>Locate mandates, verified contacts, services and emergency information for agencies under the Ministry.</p><a class="button light" href="{{ route('agencies.index') }}">Explore agencies</a></div></div></section>
@endsection
