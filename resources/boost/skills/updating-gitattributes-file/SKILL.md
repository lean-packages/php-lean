---
name: updating-gitattributes-file
description: Update .gitattributes files for projects using php-lean; use when adding missing export-ignore entries or refreshing existing content.
version: 1.0.0

tags:
- git
- gitattributes
- php-lean
- php
---

# Update .gitattributes with php-lean

Use php-lean to refresh an existing .gitattributes file:

1. Run `./vendor/bin/php-lean update` to replace missing export-ignore entries.
2. Add `--align-export-ignores` if you want aligned columns.
3. Add `--omit-header` to avoid modifying the header comment.
4. If you need a preview first, run `./vendor/bin/php-lean validate --diff` and then re-run with the `update` command.

Keep any non-export-ignore rules intact; php-lean appends export-ignores below them.
