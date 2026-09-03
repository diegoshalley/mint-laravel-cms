# Security policy

This repository may be public, but operational information must remain private.

Never commit:

- `.env` files, secrets, tokens, passwords, private keys, or server credentials
- production database exports, citizen data, enquiry submissions, logs, or backups
- unpublished ministry documents or internal security material
- private infrastructure addresses, firewall rules, or deployment credentials

Use synthetic data for development. Store secrets in the deployment platform or CI secret store. If a secret is committed, assume it is compromised: revoke it immediately and remove it from Git history.

Security vulnerabilities must not be filed as public issues. Establish a private ministry security contact before public launch.

