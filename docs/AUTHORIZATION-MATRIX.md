# CMS authorization matrix

Every entry is enforced server-side. Navigation visibility is only a usability aid and is not an authorization control.

| Capability | Editor | Reviewer | Publisher | Media Manager | Auditor | CMS Administrator |
| --- | --- | --- | --- | --- | --- | --- |
| View dashboard and content | Yes | Yes | Yes | Yes | Yes | Yes |
| Create content | Yes | No | No | No | No | Yes |
| Edit drafts and replacements | Yes | No | No | No | No | Yes |
| Submit for review | Yes | No | No | No | No | Yes |
| Review, comment, or return | No | Yes | Yes | No | No | Yes |
| Approve and publish | No | No | Yes | No | No | Yes |
| Restore a draft revision | No | No | Yes | No | No | Yes |
| Upload media | No | No | No | Yes | No | Yes |
| Approve or retire media | No | No | Yes | No | No | Yes |
| View audit history | No | No | Yes | No | Yes | Yes |
| Manage navigation | No | No | No | No | No | Yes |
| Manage redirects | No | No | No | No | No | Yes |
| Manage staff accounts | No | No | No | No | No | Yes |

The Super Administrator receives every capability and is protected by the last-active-administrator safeguard. Content and media approval also enforce maker-checker separation: authors cannot approve or publish their own work, and uploaders cannot approve their own files.
