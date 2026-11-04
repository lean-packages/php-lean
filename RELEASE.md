# Release process

This document describes how to release a new version of `php-lean` to [Packagist](https://packagist.org).

## Prerequisites

- PHP 8.2+ — the project supports PHP 8.2 through 8.6.
- Composer — for dependency management and development commands.
- Be on the `main` branch with a clean working tree.

## Release steps

1. Update `CHANGELOG.md` with the new version and a summary of changes.

2. Run the pre-release checks:
   ```bash
   composer pre-commit-check
   ```
   This runs tests, formatting, static analysis, dependency analysis, spell checking, and version alignment.

3. Bump the version and create the release commit and tag:
   ```bash
   php bin/release-version --increment patch
   ```
   To release a specific version level:
   ```bash
   php bin/release-version --increment minor
   php bin/release-version --increment major
   ```
   Or set an explicit version:
   ```bash
   php bin/release-version --version 1.2.3
   ```

4. Peel `composer.json` for distribution. This strips development-only metadata
   (`require-dev`, `scripts`, etc.) so the distributed package is lean:
   ```bash
   php vendor/bin/composer-peel peel
   ```
   Preview the changes first with `--dry-run`:
   ```bash
   php vendor/bin/composer-peel peel --dry-run
   ```
   Validate the peeled manifest before committing:
   ```bash
   php vendor/bin/composer-peel validate
   ```

5. Commit the peeled `composer.json`:
   ```bash
   git add composer.json
   git commit -m 'chore: peel composer.json for release'
   ```

6. Move the tag to include the peeled manifest:
   ```bash
   git tag -f v0.9.0
   ```

7. Push the commit and tag:
   ```bash
   git push --force-with-lease --tags
   ```
   Packagist will automatically pick up the new tag via the connected GitHub repository.

8. Rollback to restore the development `composer.json`:
   ```bash
   php vendor/bin/composer-peel rollback
   ```
   This restores the original `composer.json` from the backup (`.composer-unpeeled.json`)
   and removes the backup file.

## What `release-version` does

The `bin/release-version` script automatically:

1. Runs pre-release checks (`composer cs-lint`)
2. Verifies the repository has no uncommitted changes
3. Bumps the version in `bin/php-lean`
4. Commits the version bump with the message `Release {version}`
5. Creates a `v{version}` Git tag

It does not push the commit or tag — you must do that manually so you can review the changes first.
