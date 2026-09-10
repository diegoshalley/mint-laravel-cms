@extends('layouts.public')

@section('title', 'Documents')
@section('meta_description', 'Browse Ministry of the Interior Ghana public documents, notices, policies, reports, fees and official reference materials.')

@push('head')
<style>
.documents-hero{background:linear-gradient(135deg,#0b3b2e,#125340);color:#fff;padding:76px 0}.documents-hero__grid{display:grid;grid-template-columns:1fr .9fr;gap:70px;align-items:center}.documents-hero h1{font:800 clamp(42px,5vw,62px)/1.04 Manrope,sans-serif;letter-spacing:-.04em;margin:0}.documents-hero p:not(.eyebrow){color:#c7dbd2;font-size:17px;max-width:620px}.documents-search{background:#fff;color:var(--ink);padding:24px;border-radius:14px}.documents-search label{font-weight:800;display:block;margin-bottom:8px}.documents-search input{width:100%;border:1px solid #cddbd4;border-radius:9px;padding:13px 14px;outline:0}.documents-search small{display:block;color:var(--muted);margin-top:8px}.documents-body{padding:72px 0}.documents-toolbar{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px}.documents-toolbar button{border:1px solid var(--line);background:#fff;padding:10px 14px;border-radius:999px;font-weight:700;color:var(--muted);cursor:pointer}.documents-toolbar button.is-active{background:var(--green);color:#fff;border-color:var(--green)}.documents-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.document-card{border:1px solid var(--line);border-radius:14px;padding:24px;background:#fff;display:flex;flex-direction:column;min-height:250px}.document-card__meta{display:flex;justify-content:space-between;gap:12px;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--green);font-weight:800}.document-card h2{font:800 19px/1.28 Manrope,sans-serif;margin:18px 0 8px}.document-card p{font-size:13px;color:var(--muted);margin:0}.document-card__footer{margin-top:auto;padding-top:24px;display:flex;justify-content:space-between;gap:10px;align-items:center}.document-card__footer span{font-size:11px;color:var(--muted)}.document-card__footer a{font-size:13px;font-weight:800;color:var(--green)}.documents-note{margin-top:36px;background:var(--cream);border-radius:14px;padding:24px;display:grid;grid-template-columns:auto 1fr;gap:16px}.documents-note strong{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:var(--green);color:#fff}.documents-note h2{font:800 18px Manrope,sans-serif;margin:0 0 4px}.documents-note p{margin:0;color:var(--muted);font-size:13px}@media(max-width:900px){.documents-hero__grid{grid-template-columns:1fr;gap:30px}.documents-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:680px){.documents-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<section class="documents-hero">
    <div class="container documents-hero__grid">
        <div><p class="eyebrow eyebrow--light">Documents</p><h1>Official information, easier to find.</h1><p>Browse public notices, policies, reports, fees, statutory information and other Ministry reference materials.</p></div>
        <div class="documents-search"><label for="document-search">Search the document library</label><input id="document-search" type="search" placeholder="Search by title, topic or document type"><small>Stage 1 uses a front-end document directory. CMS-managed files and metadata come in the next stage.</small></div>
    </div>
</section>
<section class="documents-body">
    <div class="container">
        <div class="documents-toolbar" aria-label="Filter documents"><button type="button" class="is-active" data-document-filter="all">All documents</button><button type="button" data-document-filter="notice">Notices</button><button type="button" data-document-filter="policy">Policies</button><button type="button" data-document-filter="fees">Fees & charges</button><button type="button" data-document-filter="report">Reports</button></div>
        <div class="documents-grid" data-document-grid>
            <article class="document-card" data-category="notice"><div class="document-card__meta"><span>Public notice</span><span>2026</span></div><h2>Statutory Public Holidays and Commemorative Days — 2026</h2><p>Official reference information on statutory public holidays and commemorative days in the Republic of Ghana.</p><div class="document-card__footer"><span>Reference</span><a href="#">View details →</a></div></article>
            <article class="document-card" data-category="fees"><div class="document-card__meta"><span>Fees & charges</span><span>Current</span></div><h2>Ministry Fees and Charges</h2><p>Public reference information on approved fees and charges relating to Ministry services.</p><div class="document-card__footer"><span>Service information</span><a href="#">View details →</a></div></article>
            <article class="document-card" data-category="policy"><div class="document-card__meta"><span>Mandate</span><span>Official</span></div><h2>Ministry Mandate and Legislative Framework</h2><p>Reference to the Ministry's internal security and law-and-order mandate under Ghana's constitutional and civil service framework.</p><div class="document-card__footer"><span>Policy reference</span><a href="{{ route('about.index') }}">Read mandate →</a></div></article>
            <article class="document-card" data-category="report"><div class="document-card__meta"><span>Public information</span><span>Archive</span></div><h2>Annual and Sector Performance Reports</h2><p>Structured location for annual reports, sector reviews and performance publications once migrated into the CMS.</p><div class="document-card__footer"><span>Reports</span><a href="#">Browse →</a></div></article>
            <article class="document-card" data-category="notice"><div class="document-card__meta"><span>Safety advisory</span><span>Official</span></div><h2>Public Safety Advisories</h2><p>Curfews, emergency advisories and other public-safety notices published by the Ministry.</p><div class="document-card__footer"><span>Notices</span><a href="{{ route('publications.index') }}">View notices →</a></div></article>
            <article class="document-card" data-category="policy"><div class="document-card__meta"><span>Service guidance</span><span>Current</span></div><h2>Citizenship, Migration and Permit Guidance</h2><p>Official reference information supporting public applications and Ministry service requirements.</p><div class="document-card__footer"><span>Services</span><a href="{{ route('services.index') }}">Go to services →</a></div></article>
        </div>
        <p data-document-empty hidden>No documents match your search.</p>
        <div class="documents-note"><strong>i</strong><div><h2>Document integrity matters</h2><p>Published documents should eventually expose issue date, document owner, version, file type, file size and revision status so citizens can tell whether they are reading the current official version.</p></div></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(()=>{const input=document.getElementById('document-search');const cards=[...document.querySelectorAll('.document-card')];const filters=[...document.querySelectorAll('[data-document-filter]')];const empty=document.querySelector('[data-document-empty]');let category='all';const apply=()=>{const q=(input?.value||'').trim().toLowerCase();let visible=0;cards.forEach(card=>{const matchesCategory=category==='all'||card.dataset.category===category;const matchesSearch=!q||card.textContent.toLowerCase().includes(q);const show=matchesCategory&&matchesSearch;card.hidden=!show;if(show)visible++;});if(empty)empty.hidden=visible>0;};input?.addEventListener('input',apply);filters.forEach(button=>button.addEventListener('click',()=>{category=button.dataset.documentFilter;filters.forEach(item=>item.classList.toggle('is-active',item===button));apply();}));})();
</script>
@endpush
