# Product blueprint

## 1. Audit of the existing site

The current website contains valuable institutional content, but its delivery is fragmented. The homepage exposes a raw slider shortcode, repeats the navigation markup, gives news and press releases nearly identical treatment, and buries public tasks among organizational pages. It also mixes owned content, external services, social feeds, emergency numbers, and static documents without a consistent content model.

Content currently represented includes:

- Ministry profile: about, mandate, functions, policy objectives, vision, mission, and values
- Leadership: minister, chief director, former ministers, boards/councils, and agency heads
- Five directorates/units
- Eleven agencies
- e-services
- News, press releases, photo galleries, Acts, policies, RTI material, laws, public holidays, fees and charges
- Contact information and emergency numbers

The replacement should preserve authoritative content while changing the public experience from an organization chart into a citizen task system.

## 2. Public information architecture

### Primary navigation

1. **Home**
2. **About the Ministry** — mandate, leadership, directorates, boards and councils, history
3. **Services** — service directory organized around citizen needs
4. **Agencies** — searchable directory with service and emergency links
5. **News & Notices** — news, press releases, public notices, speeches, events
6. **Documents** — Acts, laws, policies, RTI, reports, fees, forms
7. **Contact** — office contacts, digital address, enquiry form, emergency contacts

### Homepage priority

1. Compact national identity/header and global search
2. Current high-priority public alert when active
3. “How can we help?” service finder
4. Latest official notices and releases
5. Ministry mandate and leadership snapshot
6. Agency directory
7. Emergency numbers in a persistent, accessible treatment
8. Latest news and media

The homepage must not lead with a decorative carousel. A carousel hides content, performs poorly, and creates accessibility and editorial problems.

## 3. CMS content model

| Model | Purpose | Key controls |
|---|---|---|
| Page | Structured institutional pages | template, parent, sections, SEO, revision |
| Article | News, release, speech, notice | type, author, dateline, publish/expiry time |
| Alert | Urgent banner or advisory | severity, audience, start/end time |
| Service | Citizen-facing service directory | eligibility, steps, fees, channels, agency |
| Agency | Ministry agency profile | contacts, services, emergency data, links |
| Directorate | Internal organizational profile | leadership, functions, contacts |
| Person | Leadership profile | office, biography, term dates, portrait |
| Document | Acts, policies, manuals, forms | category, year, version, file, accessibility |
| Event | Public calendar item | venue, dates, registration/contact |
| Gallery | Curated photo collection | captions, credits, alt text |
| Navigation | Managed menus | hierarchy, link validation, scheduling |
| Redirect | URL migration and corrections | source, destination, status code, hits |

All publishable models use a shared workflow: `draft → in_review → approved → scheduled/published → archived`. Rejection returns the item to draft with a mandatory reason.

## 4. Permissions

| Role | Capability |
|---|---|
| Super Administrator | System configuration and emergency recovery |
| CMS Administrator | Users, taxonomies, menus, redirects, settings |
| Publisher | Final approval, scheduling, unpublishing, rollback |
| Reviewer | Review, comment, request changes, recommend approval |
| Editor | Create and edit assigned content areas |
| Media Manager | Upload, replace, classify, and retire media/documents |
| Auditor | Read-only access to revisions and audit logs |

No editor can approve their own content. Emergency publishing is permission-gated and records a mandatory reason.

## 5. Non-functional requirements

- WCAG 2.2 AA target, keyboard access, semantic headings, captions, alt-text enforcement
- Responsive support from 320 px upward and graceful operation on low-bandwidth mobile networks
- Core Web Vitals budget: LCP under 2.5 s, CLS under 0.1, INP under 200 ms at the 75th percentile
- Cached public pages with immediate cache invalidation after publishing
- MFA for privileged CMS users, secure cookies, CSRF protection, throttling, security headers
- Malware scanning for uploads; MIME verification independent of file extension
- Daily encrypted database backups and versioned object-storage backups with restoration drills
- Central logs, uptime checks, queue monitoring, failed-login alerts, and immutable audit export
- Ghana Data Protection Act considerations: data minimization, purpose limitation, retention, and access logging

## 6. Initial database modules

- Identity and access: users, roles, permissions, MFA, sessions
- Publishing: content items, revisions, workflow transitions, editorial comments
- Taxonomy: categories, tags, document types, service groups
- Organization: agencies, directorates, people, offices, contacts
- Media: assets, renditions, usage references, licenses/credits
- Experience: menus, redirects, site settings, alerts, search index
- Assurance: audit events, login events, webhooks, failed jobs

## 7. Three development phases

### Phase 1 — Foundation, design system and core CMS

Confirm requirements and inventory the legacy content. Establish the Laravel application, environments, PostgreSQL schema, authentication, MFA, role permissions, audit logging, maker-checker workflow, media library, navigation manager, reusable public design system, automated tests, and CI checks.

**Phase gate:** an editor can create content, a different reviewer can return or recommend it, and an authorized publisher can publish or schedule it. Every transition and revision is recoverable and auditable.

### Phase 2 — Public website, services and migration

Build the complete public experience: homepage, Ministry pages, leadership, directorates, agency directory, service finder, news and notices, document library, events, galleries, site-wide search, contact and emergency information. Clean, classify, import, and redirect approved legacy content.

**Phase gate:** all approved page types work responsively; search covers the required content; priority legacy URLs resolve correctly; and content owners sign off the migrated material.

### Phase 3 — Assurance, deployment and handover

Complete accessibility, security, load, backup-and-restore and editorial acceptance testing. Configure production infrastructure, monitoring, encrypted backups, deployment automation and rollback. Train ministry staff, freeze legacy content, perform the controlled cutover, monitor launch, and hand over operational documentation.

**Phase gate:** acceptance evidence is signed, restoration has been tested, staff can operate the CMS, monitoring is active, and the production launch and rollback procedures have both been rehearsed.

## 8. Definition of done for the first release

- Approved public page templates and mobile layouts
- CMS workflow proven with separate editor, reviewer, and publisher accounts
- All priority legacy URLs mapped or redirected
- Site-wide search covers pages, services, news, agencies, and documents
- Document downloads have correct titles, file types, sizes, and accessible alternatives where required
- Critical accessibility, security, backup-restore, and performance tests passed
- Ministry staff trained with a concise editorial handbook
