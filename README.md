# php-lean

Validate and manage `.gitattributes` export-ignore rules to keep your published PHP packages lean.

## Why?

When you publish a PHP package to Packagist, Composer packages up every file in your repository that Git tracks — unless
you explicitly exclude it using `.gitattributes` export-ignore directives. That means your published distribution archive
can unintentionally include development artefacts like IDE settings, test suites, static analysis configuration, CI
workflows, and documentation. These files bloat the download size, slow down `composer require`, and expose internal
project structure to downstream users.

`php-lean` makes sure your `.gitattributes` export-ignore list is complete so only the source code your users need ends
up in the published distribution archive.

## Installation

### Via Composer (global)

```bash
composer global require stolt/php-lean
```

Make sure that the path to your global vendor binaries directory is in your `$PATH`. You can find it with
`composer global config bin-dir --absolute`.

### Via Composer (dev dependency)

```bash
composer require --dev stolt/php-lean
```

Installing as a dev dependency allows further utilisation via [Composer scripts](#ci-integration).

## Usage

```
php-lean <COMMAND>
```

### Commands

| Command            | Description                                                        |
|--------------------|--------------------------------------------------------------------|
| `lint`             | Check if all repository artefacts are export-ignored.              |
| `create`           | Add missing artefacts to the export-ignore list.                   |
| `update`           | Replace the export-ignore list, preserving custom entries.         |
| `reformat`         | Reformat an existing `.gitattributes` file.                        |
| `validate-remote`  | Validate a remote GitHub repository's `.gitattributes`.            |
| `init`             | Create an initial `.lpv` configuration file.                       |
| `refresh`          | Refresh an existing `.lpv` file with missing preset patterns.      |
| `tree`             | Inspect the source and dist package file structure.                |

### `lint` — Check export-ignore completeness

Verifies that all detected repository artefacts are listed in `.gitattributes` export-ignore directives.

```bash
# Check the current directory
php-lean lint

# Check a specific directory
php-lean lint /path/to/project

# Show a diff of what would change
php-lean lint --diff

# Enforce strict order comparison
php-lean lint --enforce-strict-order

# Validate the actual Git archive content
php-lean lint --validate-git-archive

# Show distribution impact report
php-lean lint --report-impact
```

**`--diff` flag:** Shows which artefacts would be added or removed from the export-ignore list, using a unified diff
format. This is useful for previewing changes before running `create`.

### `create` — Add missing export-ignores

Adds any detected artefacts that are not yet in the export-ignore list. Preserves existing entries.

```bash
# Dry run: see what would be added
php-lean create --dry-run

# Apply changes
php-lean create

# Force overwrite an existing file
php-lean create --force
```

### `update` — Replace export-ignore list

Replaces the entire export-ignore list with the artefacts that currently exist in the repository, merging built-in
default patterns with any custom entries already in `.gitattributes`. This removes stale entries (files no longer
present) and adds new ones.

```bash
# Dry run: see what would change
php-lean update --dry-run

# Apply changes
php-lean update
```

### `reformat` — Reformat `.gitattributes`

Reformats an existing `.gitattributes` file for better readability.

```bash
# Dry run: see what would change
php-lean reformat --dry-run

# Sort alphabetically
php-lean reformat --sort-alphabetically

# Sort from directories to files
php-lean reformat --sort-from-directories-to-files

# Group export-ignore and non-export-ignore entries
php-lean reformat --group
```

## Options and flags

All commands support these common options and flags:

| Option / Flag                  | Description                                                    |
|--------------------------------|----------------------------------------------------------------|
| `<directory>`                  | Path to project root (default: current directory)              |
| `--glob-pattern <PATTERN>`     | Custom artefact patterns, e.g. `'{.*,*.md,docs/}'`             |
| `--glob-pattern-file <PATH>`   | Load patterns from a file (`.lpv` file used automatically)     |
| `--preset <PRESET>`            | Use a predefined preset (default: `PHP`)                       |
| `--diff`                       | Show diff-style output (`lint`)                                |
| `--dry-run`                    | Preview changes without modifying files (`create`, `update`)   |
| `--enforce-strict-order`       | Enforce strict order comparison (`lint`)                       |
| `--enforce-alignment`          | Enforce strict alignment of export-ignores (`lint`)            |
| `--validate-git-archive`       | Validate actual Git archive content (`lint`)                   |
| `--report-impact`              | Show distribution impact report (`lint`)                       |
| `--report-stale-export-ignores`| Report export-ignores for non-existent files (`lint`)          |
| `--keep-license`               | Allow license file in dist archive (`lint`)                    |
| `--keep-glob-pattern <PATTERN>`| Keep matching files in dist archive (`lint`)                   |
| `--stdin-input`                | Read `.gitattributes` content from STDIN (`lint`)              |
| `--flavour <FLAVOUR>`          | Export-ignore flavour: `classic` or `negated` (`create`)       |

## Default artefact patterns

By default, `php-lean` checks for these glob patterns (defined in the [PHP preset](./src/Presets/PhpPreset.php)):

```
.*                                    Dotfiles and directories (e.g., .github/, .idea/, .vscode/)
*.txt                                 Text files
*.{md,MD}                             Markdown files (except README — see below)
*.rst                                 reStructuredText files
*.toml                                TOML configuration files (except phast.toml)
*.xml                                 XML files
*.yml                                 YAML files
*.dist.*                              Distribution config files
*.lock                                Lock files (e.g., composer.lock)
LICENSE                               Generic license file
{D,d}oc*                              Documentation directories
{T,t}est* / {S,s}pec*                Test and spec directories
AGENT.md, AGENTS.md, CLAUDE.md, ...   AI assistant instruction files
.aiassistant/, .cursor/, .claude/     AI assistant directories
llms.txt, llms-full.txt               AI context files
phpstan*, phpunit*, rector*, ...      PHP tooling configuration
vendor*                               Vendor directories
```

**Note:** `README.md` is always kept in the distribution archive by default and is excluded from the `*.{md,MD}` pattern.
Other Markdown files like `CHANGELOG.md`, `CONTRIBUTING.md`, and `RELEASE.md` are still export-ignored.
Similarly, `phast.toml` is excluded from the `*.toml` pattern and kept in the distribution archive.

Override them with `--glob-pattern`:

```bash
php-lean lint --glob-pattern '{.*,*.md,docs/}'
```

Or create a `.lpv` file in your repository to persist custom patterns:

```bash
php-lean init
```

## `.gitignore` awareness

All commands automatically read the `.gitignore` file in the project directory. Artefacts already covered by
`.gitignore` entries are excluded from the results, since Git will not track them anyway.

- **`lint`**: Will not flag artefacts that are already in `.gitignore`.
- **`create`**: Will not add artefacts that are already in `.gitignore` to the export-ignore list.
- **`update`**: Will not include artefacts that are already in `.gitignore` in the new export-ignore list.

## CI integration

Add `php-lean lint` to your CI pipeline to enforce that export-ignore lists stay complete.

### Composer script

By adding the following to your project's `composer.json`, the `.gitattributes` file can be validated via
`composer validate-gitattributes`:

```json
{
    "scripts": {
        "validate-gitattributes": "php-lean lint"
    }
}
```

### GitHub Action

A reusable GitHub Action is provided to validate `.gitattributes` files in CI workflows.

```yaml
- name: Validate .gitattributes
  uses: ./.github/actions/lint
  with:
    options: '--enforce-strict-order --enforce-alignment --diff'
```

See the [action documentation](.github/actions/lint/README.md) for more details.

### cpx

Run the validator ad-hoc via [cpx](https://github.com/laravel/cpx):

```bash
cpx stolt/php-lean lint
```

## JSON output

All commands auto-detect agentic runs, switching the output from human-readable text to a structured JSON object.
This is useful for AI workflows or automation pipelines where machine-readable output is preferred.

```bash
export COPILOT_MODEL=1
php-lean lint
```

```json
{
    "command": "lint",
    "status": "success",
    "message": "The .gitattributes file is considered valid.",
    "valid": true
}
```

Each response always includes `command`, `status` (`success` or `failure`), and `message` fields. Commands also include additional context-specific fields:

| Command    | Additional fields on success                                                                                         |
|------------|----------------------------------------------------------------------------------------------------------------------|
| `lint`     | `valid`, `warnings` (if any), `expected_gitattributes_content` (on failure), `archive_valid`, `unexpected_artifacts` |
| `create`   | `gitattributes_file_path`                                                                                            |
| `update`   | `gitattributes_file_path`                                                                                            |
| `init`     | `lpv_file_path`                                                                                                      |
| `refresh`  | `lpv_file_path`                                                                                                      |
| `tree`     | `package`, `tree`                                                                                                    |

## AI disclaimer

This package is developed with the assistance of Artificial Intelligence (AI). AI tools are used throughout the development
process, including code generation, documentation, testing, and maintenance.

While reasonable care is taken to review and validate AI-generated contributions, AI-generated code is not inherently correct,
secure, or free of defects. All contributions remain subject to human review and project quality standards.

As with any software, use this package at your own risk. No guarantee is made regarding its fitness for a particular purpose,
and the maintainers assume no liability for damages resulting from its use.

## Contributing

If you're considering contributing to this project, have a look at this repository's [CONTRIBUTING.md](CONTRIBUTING.md)
for more advice.

## License

This library and its CLI are licensed under the MIT license. Please see [LICENSE.md](LICENSE.md) for more details.

## Changelog

All noteworthy changes are documented in the [CHANGELOG.md](CHANGELOG.md).

## Related projects

- [cargo-lean](https://github.com/lean-packages/cargo-lean) — The `Rust` equivalent.
- [py-lean](https://github.com/lean-packages/py-lean) — The `Python` equivalent.
- [js-lean](https://github.com/lean-packages/js-lean) — The `JavaScript` equivalent.
- [zig-lean](https://github.com/lean-packages/zig-lean) — The `Zig` equivalent.
