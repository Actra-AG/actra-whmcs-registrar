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

- WHMCS registrar module for the Actra registrar API, Composer package `actra/actra-whmcs-registrar` (MIT, public).
  Used by WHMCS installations of Actra and its customers to register, transfer and manage domains.
- PHP version: 8.3, for the module and the development tools (DDEV), because WHMCS only supports PHP 8.3.
- Releases are Git tags; the GitHub release is created from [CHANGELOG.md](CHANGELOG.md) when the tag is pushed.
- The repository is public, the application behind the API is not: texts of the repository (README, CHANGELOG, texts
  of the module, comments, commit messages) call it "the Actra registrar API" or "Actra", never by its internal name.
- Ongoing goal: reduce `phpstan-baseline.neon` (legacy code from before the coding standard was introduced).

## Project-specific rules

- Directories:
  - `src/actra/` is the module directory deployed to `modules/registrars/actra` in WHMCS. `actra.php` holds the WHMCS
    entry functions (`actra_*`, see https://developers.whmcs.com/domain-registrars/function-index/); they only
    delegate to an action class.
  - `src/actra/lib/action/`: one class per WHMCS function, maps WHMCS parameters to API calls and back.
  - `src/actra/lib/api/`: one class per Actra registrar API endpoint, `ApiClient` does the HTTP (cURL) requests.
  - `src/actra/lib/data/`, `src/actra/lib/settings/`: value objects, enums and module settings.
- The module has no runtime dependencies besides PHP and WHMCS. WHMCS classes (`WHMCS\Domain\…`,
  `WHMCS\Carbon`, …) are not available in this repository. New code declares the WHMCS classes it uses in
  `stubs/whmcs.stub` (as documented at https://classdocs.whmcs.com), the legacy code's errors are in the baseline.
- Languages: German and English. Every text shown to admins or clients is in `src/actra/lang/english.php` and
  `src/actra/lang/german.php` (Swiss spelling, no ß) and is read with the `Translator`; a test checks that both files
  have the same keys and placeholders. Templates get the texts as `$actraLang` and escape them. Error messages of the
  Actra registrar API are shown as received.
- The API classes follow the OpenAPI specification of the Actra registrar API (`GET <API URL>/openapi` with an API
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
- **CHANGELOG.md instead of UPGRADE.md:** the readers are WHMCS administrators, not developers using a PHP API. One
  section per version (`## vX.Y.Z - YYYY-MM-DD`, newest first) with `Added`, `Fixed`, `Changed` and the settings
  administrators must change. As for `UPGRADE.md`, there is no "Unreleased" section: the commit that releases a
  version adds its section.
