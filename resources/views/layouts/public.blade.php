<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Official website of Ghana's Ministry of the Interior">
    <title>@yield('title', 'Ministry of the Interior')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<div class="govbar"><div class="container">Official Government of Ghana website <span>🇬🇭</span></div></div>
<header class="site-header">
    <div class="container masthead">
        <a class="brand" href="{{ route('home') }}" aria-label="Ministry of the Interior home">
            <span class="crest">GH</span><span><strong>MINISTRY OF THE INTERIOR</strong><small>Republic of Ghana</small></span>
        </a>
        <div class="header-actions"><a href="{{ route('contact') }}">Contact</a><a class="emergency" href="#emergency">Emergency contacts</a></div>
    </div>
    <nav class="nav" aria-label="Primary navigation"><div class="container">
        @forelse($primaryNavigation as $item)<span class="nav-item"><a href="{{ $item->destination }}" @if($item->open_in_new_tab)target="_blank" rel="noopener noreferrer"@endif>{{ $item->label }}</a>@if($item->children->isNotEmpty())<span class="submenu">@foreach($item->children as $child)<a href="{{ $child->destination }}" @if($child->open_in_new_tab)target="_blank" rel="noopener noreferrer"@endif>{{ $child->label }}</a>@endforeach</span>@endif</span>@empty
        <a href="{{ route('home') }}">Home</a><a href="#about">About</a><a href="{{ route('services.index') }}">Services</a><a href="{{ route('agencies.index') }}">Agencies</a><a href="{{ route('publications.index') }}">News & Notices</a><a href="{{ route('documents.index') }}">Documents</a>@endforelse
        <form role="search" action="/search"><label class="sr-only" for="q">Search</label><input id="q" name="q" placeholder="Search this website"><button>Search</button></form>
    </div></nav>
</header>
<main id="main">@yield('content')</main>
<footer id="emergency" class="footer"><div class="container footer-grid">
    <div><strong>Ministry of the Interior</strong><p>P.O. Box M42, Accra, Ghana</p></div>
    <div><strong>Emergency numbers</strong><p>Police: <a href="tel:191">191</a> · Fire: <a href="tel:192">192</a> · Ambulance: <a href="tel:193">193</a></p></div>
    <div><a href="{{ route('contact') }}">Contact the Ministry</a><br><a href="{{ route('login') }}">Staff sign in</a></div>
</div></footer>
</body></html>
