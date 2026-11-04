# Lean Package Validator GitHub Action

Lints the `.gitattributes` file using the `lint` command from [php-lean](https://github.com/stolt/php-lean).

This action performs self-validation, checking the project's own `.gitattributes` file to ensure it's correctly 
formatted and functional.

## Usage

```yaml
- name: Lint .gitattributes
  uses: ./.github/actions/lint
```

### With custom PHP version

```yaml
- name: Lint .gitattributes
  uses: ./.github/actions/lint
  with:
    php-version: '8.4'
```

### With additional lint options

```yaml
- name: Lint .gitattributes
  uses: ./.github/actions/lint
  with:
    options: '--enforce-strict-order --enforce-alignment --diff'
```

## Inputs

| Input | Description | Required | Default |
|-------|-------------|----------|---------|
| `php-version` | PHP version to use | No | `8.3` |
| `options` | Additional options/flags passed to the lint command | No | `` |

## Example workflow

```yaml
name: lint

on:
  push:
    paths:
      - '.gitattributes'

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Lint .gitattributes
        uses: ./.github/actions/lint
```

## Using the GitHub Action from another repository

### Reference the action directly

Reference the action directly from this repository using the full GitHub path. This keeps you up to date without copying
files.

```yaml
- name: Lint .gitattributes
  uses: stolt/php-lean/.github/actions/lint@main
  with:
    php-version: '8.4'
    options: '--enforce-strict-order --enforce-alignment'
```

You can pin to a specific tag (e.g., `@v6.4.0`) for stability. The action runs inside your repository's context, so ensure
you've checked out your code first with `actions/checkout`.
