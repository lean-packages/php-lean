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

3. Execute the release:
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

4. Push the commit and tag:
   ```bash
   git push && git push --tags
   ```
   Packagist will automatically pick up the new tag via the connected GitHub repository.

## What `release-version` does

The `bin/release-version` script automatically:

1. Runs pre-release checks (`composer cs-lint`)
2. Verifies the repository has no uncommitted changes
3. Bumps the version in `bin/php-lean`
4. Commits the version bump with the message `Release {version}`
5. Creates a `v{version}` Git tag

It does not push the commit or tag — you must do that manually so you can review the changes first.
