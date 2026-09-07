@extends('layouts.public')

@section('title', 'About the Ministry')
@section('meta_description', 'Learn about the Ministry of the Interior Ghana, its mandate, leadership, directorates and role in public safety and national security.')

@section('content')
<section class="page-hero page-hero--about">
    <div class="container page-hero__grid">
        <div>
            <p class="eyebrow eyebrow--light">About the Ministry</p>
            <h1>Leadership for a safer and more secure Ghana.</h1>
            <p>The Ministry provides policy direction, coordination and oversight for internal security, public safety, migration and related national services.</p>
        </div>
        <div class="about-hero-panel">
            <span>Our purpose</span>
            <strong>Peace. Security. Public Safety.</strong>
            <p>Working through strong institutions, accountable leadership and citizen-centred public service.</p>
        </div>
    </div>
</section>

<section class="about-nav" aria-label="About page sections">
    <div class="container about-nav__inner">
        <a href="#mandate">Mandate</a>
        <a href="#vision">Vision & mission</a>
        <a href="#leadership">Leadership</a>
        <a href="#directorates">Directorates</a>
        <a href="#values">Core values</a>
    </div>
</section>

<section class="section about-mandate" id="mandate">
    <div class="container about-mandate__grid">
        <div>
            <p class="eyebrow">Our mandate</p>
            <h2>Coordinating Ghana's internal security and public safety system.</h2>
        </div>
        <div>
            <p class="lead-copy">The Ministry of the Interior exists to formulate and coordinate policies that strengthen national security, public safety, migration management and the effective delivery of services through its agencies.</p>
            <div class="about-stat-grid">
                <div><strong>11</strong><span>Agencies and institutions under the Ministry</span></div>
                <div><strong>24/7</strong><span>National emergency and public safety response</span></div>
                <div><strong>Nationwide</strong><span>Public service reach across Ghana</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section about-purpose" id="vision">
    <div class="container">
        <div class="purpose-grid">
            <article>
                <span>01</span>
                <p class="eyebrow">Vision</p>
                <h2>A safe, secure and peaceful Ghana.</h2>
                <p>We work toward a society in which people, communities and institutions can thrive in safety and confidence.</p>
            </article>
            <article>
                <span>02</span>
                <p class="eyebrow">Mission</p>
                <h2>Lead, coordinate and strengthen internal security.</h2>
                <p>We provide policy leadership and oversight to support effective, accountable and citizen-focused public safety institutions.</p>
            </article>
        </div>
    </div>
</section>

<section class="section leadership-section" id="leadership">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div>
                <p class="eyebrow">Leadership</p>
                <h2>Responsible leadership. Clear public accountability.</h2>
            </div>
            <p>The public website should make leadership visible without turning the homepage into an organizational chart.</p>
        </div>
        <div class="leadership-grid">
            <article class="leader-card leader-card--primary">
                <div class="leader-card__portrait" aria-hidden="true"><span>Minister</span></div>
                <div class="leader-card__body">
                    <p class="meta">Minister for the Interior</p>
                    <h3>Ministerial Leadership</h3>
                    <p>Provides political leadership and strategic direction for the Ministry and its agencies.</p>
                    <a href="#">View profile →</a>
                </div>
            </article>
            <article class="leader-card">
                <div class="leader-card__portrait leader-card__portrait--secondary" aria-hidden="true"><span>Chief Director</span></div>
                <div class="leader-card__body">
                    <p class="meta">Chief Director</p>
                    <h3>Administrative Leadership</h3>
                    <p>Leads the Ministry's administrative, technical and operational coordination.</p>
                    <a href="#">View profile →</a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section directorates-section" id="directorates">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div><p class="eyebrow">Directorates & units</p><h2>The teams behind policy and coordination.</h2></div>
            <p>Directorates support planning, finance, administration, policy delivery, monitoring and sector coordination.</p>
        </div>
        <div class="directorate-grid">
            <article><span>01</span><h3>Finance & Administration</h3><p>Financial stewardship, administration and institutional support.</p></article>
            <article><span>02</span><h3>Policy Planning, Monitoring & Evaluation</h3><p>Policy development, planning, monitoring and performance oversight.</p></article>
            <article><span>03</span><h3>Human Resource Management</h3><p>Workforce planning, development and administrative support.</p></article>
            <article><span>04</span><h3>Research, Statistics & Information Management</h3><p>Evidence, data and information systems supporting Ministry decisions.</p></article>
            <article><span>05</span><h3>Internal Audit</h3><p>Independent assurance, controls and accountability support.</p></article>
            <article><span>06</span><h3>Public Affairs</h3><p>Public information, stakeholder communication and media engagement.</p></article>
        </div>
    </div>
</section>

<section class="section values-section" id="values">
    <div class="container values-grid">
        <div><p class="eyebrow eyebrow--light">Core values</p><h2>How we expect public service to be delivered.</h2></div>
        <div class="values-list">
            <div><strong>Integrity</strong><span>Acting lawfully, ethically and transparently.</span></div>
            <div><strong>Professionalism</strong><span>Delivering competent, respectful public service.</span></div>
            <div><strong>Accountability</strong><span>Taking responsibility for decisions and outcomes.</span></div>
            <div><strong>Service</strong><span>Putting citizens and public safety at the centre.</span></div>
        </div>
    </div>
</section>
@endsection
