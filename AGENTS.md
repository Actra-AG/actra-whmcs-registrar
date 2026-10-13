# AGENTS.md

Persistent instructions for developers and AI assistants working in this repository.

## Global standard

This project follows the Actra coding standard, installed as development dependency `actra/coding-standard`
(https://github.com/Actra-AG/coding-standard).

- Read [vendor/actra/coding-standard/AGENTS.md](vendor/actra/coding-standard/AGENTS.md) and the standards linked
  there before working on this project. They are binding. If `vendor/` is missing, run `composer install` first.
- The rules below only **add** project-specific rules or state explicit deviations (with reason). They take precedence
  over the global standard where they conflict.

## Project context

- WHMCS registrar module for the Actra Domains REST API, Composer package `actra/actra-whmcs-registrar` (MIT, public).
  Used by WHMCS installations of Actra and its customers to register, transfer and manage domains.
- PHP version: 8.3, for the module and the development tools (DDEV), because WHMCS only supports PHP 8.3.
- Releases are Git tags; every release is listed in [CHANGELOG.md](CHANGELOG.md).
- Ongoing goal: reduce `phpstan-baseline.neon` (legacy code from before the coding standard was introduced).

## Project-specific rules

- Directories:
  - `src/actra/` is the module directory deployed to `modules/registrars/actra` in WHMCS. `actra.php` holds the WHMCS
    entry functions (`actra_*`, see https://developers.whmcs.com/domain-registrars/function-index/); they only
    delegate to an action class.
  - `src/actra/lib/action/`: one class per WHMCS function, maps WHMCS parameters to API calls and back.
  - `src/actra/lib/api/`: one class per Actra Domains API endpoint, `ApiClient` does the HTTP (cURL) requests.
  - `src/actra/lib/data/`, `src/actra/lib/settings/`: value objects, enums and module settings.
- The module has no runtime dependencies besides PHP and WHMCS. WHMCS classes (`WHMCS\Domain\…`,
  `WHMCS\Carbon`, …) are not available in this repository; their PHPStan errors are in the baseline.
- The API classes follow the OpenAPI specification of the Actra Domains REST API (`GET <API URL>/openapi` with an API
  key). Read the current version before changing API calls; the repository keeps no copy. Mismatches between the API
  and its specification are fixed in the API, not worked around here.
- Run the checks with `ddev composer check` (DDEV project without database, PHP 8.3). The module itself is checked in
  a WHMCS installation; there is no local WHMCS.
- No HTML/CSS frontend review: the only template is `src/actra/dnssec.tpl`.

## Deviations from the global standard

- **PHP 8.3 instead of 8.5:** WHMCS only supports PHP 8.3, so the module and all development tools run on PHP 8.3.
  - Do not use PHP 8.4/8.5 features (property hooks, asymmetric visibility, `new` without parentheses, pipe
    operator, …).
  - `.ddev/config.yaml` uses PHP 8.3. `phpstan.neon` sets `phpVersion: 80300`. `.php-cs-fixer.dist.php` replaces the
    `@PHP8x5Migration` rule sets with `@PHP8x3Migration`, so the fixer does not introduce PHP 8.4/8.5 syntax.
  - `actra/coding-standard` requires PHP 8.5, but its configuration runs on PHP 8.3. DDEV sets
    `COMPOSER_IGNORE_PLATFORM_REQ=php`, so Composer installs it on PHP 8.3. Because this disables the PHP check for all
    packages, `require-dev` pins every dependency whose current major version needs PHP 8.4+: the Symfony components
    of PHP-CS-Fixer (`^7.4`) and PHPUnit (`^12.5`). After `composer update`, check that no other package needs a
    newer PHP (`ddev composer check` fails with a parse error if one does) and pin it the same way.
