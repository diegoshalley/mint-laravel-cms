# Phase 1 delivery status

## Implemented in the first vertical slice

- Laravel 12 application bootstrap and HTTP entry point
- UUID users and session storage
- CMS authentication with throttled login and session regeneration
- Role and permission model with seven editorial roles
- Draft, revision, review, approval, scheduling and publishing domain model
- Maker-checker enforcement: an author cannot approve or publish their own content
- Recorded workflow transitions and recoverable revision snapshots
- Scheduled publication command with row locking
- CMS overview, content library and editor screens
- Citizen-first public homepage and core navigation shells
- Security response headers
- PostgreSQL CI pipeline with migrations, formatting and feature tests

## Still required before the Phase 1 gate can pass

- Complete TOTP MFA enrollment, challenge, recovery codes and enforcement tests
- Media upload validation, virus-scanning adapter and accessible metadata rules
- Navigation manager and redirect administration
- Immutable audit-event writer and administrative audit viewer
- Revision comparison and rollback interface
- Editorial comments and reviewer recommendation step
- User administration and account lifecycle controls
- Complete automated authorization and browser-flow coverage
- Performance and accessibility baselines

The existence of screens is not acceptance evidence. Phase 1 passes only after separate editor,
reviewer and publisher accounts complete the workflow in automated and acceptance tests.
