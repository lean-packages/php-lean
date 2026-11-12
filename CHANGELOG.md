# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to
[Semantic Versioning](http://semver.org/).

## [Unreleased]

## [0.9.3] - 2026-11-12

### Added

- `phast.toml` is now included in distribution archives when present, while other TOML files remain export-ignored by default.

## [0.9.2] - 2026-11-07

### Changed

- __README.md is now always kept__ in distribution archives by default. The `--keep-readme` option has been removed, as 
README files are essential for package documentation and should always be included.

## [0.9.1] - 2026-11-04

### Fixed

- `composer-peel`ed release.

## [0.9.0] - 2026-11-04

### Added

- __`lint` command__: Checks whether all repository artefacts are listed in `.gitattributes` export-ignore directives.
- __`create` command__: Adds missing artefacts to the `.gitattributes` export-ignore list, preserving existing entries.
- __`update` command__: Replaces the `.gitattributes` export-ignore list with only the artefacts that currently exist in the repository.
- __`reformat` command__: Reformats an existing `.gitattributes` file (alignment, ordering, deduplication).
- __`validate-remote` command__: Validates the export-ignore list of a remote Git repository without cloning it locally.
- __`init` command__: Creates a `.lpv` file with preset glob patterns for a project.
- __`refresh` command__: Refreshes an existing `.lpv` file with missing preset patterns.
- __`tree` command__: Inspects the source and dist package file structure.
- __`--format json` option__: All commands support JSON output for scripting and AI interoperability.
- __`--diff` flag__ (lint command): Shows a diff-style preview of which artefacts would be added or removed from the export-ignore list.
- __`--dry-run` flag__ (create/update commands): Preview changes without modifying the `.gitattributes` file.
- __`--glob-pattern` option__: Override the default list of artefact patterns with custom ones.
- __`--glob-pattern-file` option__: Load artefact patterns from a `.lpv` file.
- __`--preset` option__: Use a predefined preset of artefact patterns (e.g., `PHP`, `Common`).
- __`--validate-git-archive` flag__ (lint command): Validates the actual Git archive content against the export-ignore list.
- __`--report-impact` flag__ (lint command): Shows a distribution impact report summarizing archive size differences.
- __`--enforce-strict-order` flag__ (lint command): Enforces strict order comparison of export-ignore entries.
- __`--enforce-alignment` flag__ (lint command): Enforces strict alignment of export-ignore directives.
- __`--stdin-input` flag__ (lint command): Reads `.gitattributes` content from STDIN for CI pipeline integration.
- __`.gitignore` awareness__: All commands automatically read `.gitignore` and exclude already-ignored artefacts from results.
- __Negated export-ignore flavour__: Support for both classic and negated (`!`) export-ignore directive styles.
- __GitHub Action__: A reusable action for validating `.gitattributes` in CI workflows.
- __AI Skills__: Bundled Agent Skills for creating, updating, and validating `.gitattributes` files.
