# Contributing

Thank you for your interest in contributing to `php-lean`.

## Prerequisites

- PHP 8.2+ — the project supports PHP 8.2 through 8.6.
- Composer — for dependency management and development commands.

## Getting Started

After cloning the repository, follow these steps to set up the project:

```bash
git clone git@github.com:lean-packages/php-lean.git && cd php-lean
composer install
```

Verify the setup. Run all checks to confirm everything works:

```bash
composer pre-commit-check
```

## Code Quality

This project uses Composer scripts to manage common development commands.

See all available scripts with:

```bash
composer run
```

### Pre-commit check (recommended)

Run tests, formatting, static analysis, dependency analysis, spell checking, and version alignment in one command:

```bash
composer pre-commit-check
```

### Formatting

Check formatting (enforced in CI):

```bash
composer cs-lint
```

Auto-fix formatting issues:

```bash
composer cs-fix
```

### Static Analysis

Check for type errors and code issues via PHPStan:

```bash
composer static-analyse
```

### Dependency Analysis

Find shadowed or unused dependencies:

```bash
composer dependency-analyse
```

### Validating leanness

Validate the `.gitattributes` export-ignore list against the current repository state:

```bash
composer validate-gitattributes
```

### Spell Checking

Check for spelling errors:

```bash
composer spell-check
```

### Testing

Run the full test suite:

```bash
composer test
```

Run tests with code coverage:

```bash
composer test-with-coverage
```

## Pull Requests

Please open pull requests against the `main` branch from a dedicated feature branch. Use descriptive branch names that
reflect the change, such as:

- `feat/lint-command`
- `fix/export-ignore-order`
- `docs/update-readme`

Before opening a PR, ensure:

1. `composer pre-commit-check` passes locally (tests, lint, static analysis, dependency analysis, spell check, and version alignment).
2. `CHANGELOG.md` is updated under the `[Unreleased]` section with a summary of changes.
3. All commit messages follow [Conventional Commits](https://www.conventionalcommits.org/).

## Commit Messages

Please follow [Conventional Commits](https://www.conventionalcommits.org/) for all commit messages. This keeps the history readable
and enables automated changelog generation. You can inspect the message schema for this project via `git log --oneline -n 10`.

Common types used in this project:

- `feat:` — new features or behavior changes
- `fix:` — bug fixes
- `docs:` — documentation updates
- `refactor:` — code changes that neither fix bugs nor add features
- `test:` — adding or updating tests
- `chore:` — maintenance tasks (dependencies, config, etc.)
