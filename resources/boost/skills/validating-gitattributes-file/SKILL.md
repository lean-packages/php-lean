---
name: validating-gitattributes-file
description: Validate .gitattributes files in projects using php-lean; use when checking export-ignore completeness, ordering, or stale entries.
version: 1.0.0

tags:
- git
- gitattributes
- php-lean
- php
---

# Validate .gitattributes with php-lean

From the repository root:

1. Run `./vendor/bin/php-lean validate` to check the current .gitattributes file.
2. Add `--diff` to see the expected content when validation fails.
3. Add `--enforce-strict-order` or `--enforce-alignment` only if strict comparison is required.
4. Add `--report-stale-export-ignores` (with `--diff`) to surface export-ignores that no longer exist.

If validation fails, use the updating skill to bring .gitattributes in sync.
