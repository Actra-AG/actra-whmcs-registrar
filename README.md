# Actra WHMCS Registrar Module

Actra Registrar Module for WHMCS.

## Description

This module provides functionality for registering, transferring, and renewing domains through Actra's registrar API
within the WHMCS platform.

The module communicates with the Actra registrar API using bearer-token authentication and JSON requests.

Domain properties such as the registration status, the transfer lock, and the restorable state are read from the
normalized fields of the API response. The registry-specific status codes are therefore interpreted by the Actra
registrar API rather than by this module, which requires an API that provides the `normalizedStatus` field and the
derived boolean fields of the domain response.

## Features

- Register Domain
- Transfer Domain
- Renew Domain (where applicable)
- Get EPP Code
- Manage Nameservers
- Manage Contact Details
- DNSSEC Support

## Known Issues

### WHMCS 9.0.x: persistent registrar configuration warning

WHMCS 9.0.x has a confirmed admin-area session handling issue where a registrar configuration validation error can
remain visible after the configuration has subsequently been corrected and saved successfully.

This can happen when the registrar configuration validation initially fails, for example because of an invalid API key.
WHMCS stores the validation message in the admin session as a configuration warning. In affected WHMCS versions, a later
successful validation does not reliably clear that session warning during the admin-area redirect lifecycle.

Symptoms:

- The Actra API returns a successful response when validating the corrected credentials.
- WHMCS still displays the previous `Action Required` message.
- The message disappears after logging out and back in, or when using a fresh admin session.

This is a visual WHMCS admin UI issue only. It does not affect the saved registrar configuration or the actual registrar
module functionality.

Workaround:

- Log out of the WHMCS admin area and log back in.
- Alternatively, use a fresh browser session.

This module intentionally does not implement a local workaround for this issue, because reliable programmatic mitigation
would require changes outside the module directory, such as a global WHMCS hook, which is outside the deployment scope
of this Composer-installed registrar module.

## Requirements

- WHMCS with PHP 8.3.
- Module v0.7.0 and newer need the Actra registrar API v1.1.0 or newer. Older module versions need an older API
  version; see the [CHANGELOG](CHANGELOG.md).

## Contacts

Contacts are created at Actra without a registry and are referenced by their public contact ID. Actra creates the
contact at the registry of the domain when the domain is registered or changed, and reuses contacts with identical
data. The module stores no contact IDs or registry handles.

Contact changes are sent as a regular domain update. If the registry declines a change of contacts (for example
because it requires a paid owner change), an administrator at Actra checks the change, and the module shows a
corresponding error message.

## Transfer Behavior

Incoming domain transfers submit WHMCS contact data directly to the Actra registrar API. Actra resolves the appropriate
registry from the TLD and creates or reuses matching contacts automatically.

When available, separate WHMCS Admin, Tech, and Billing contact data is submitted for the transfer. If role-specific
contact data is incomplete or unavailable, the module falls back to the registrant contact data.

## Languages

The texts of the module are available in German and English. WHMCS shows them in the language of the admin in the
admin area and in the language of the client in the client area; other languages fall back to English. The module
explains contact errors and an unreachable registry in these languages; other error messages of the Actra registrar API
are shown in English.

## Installation

WHMCS loads the module from `modules/registrars/actra` and autoloads its classes from the `lib` directory there. The
module has no runtime dependencies.

### Via Composer (Recommended)

Do not run Composer in the WHMCS root directory: WHMCS ships its own `vendor` directory, which Composer would
overwrite. Install the package in a separate directory outside the WHMCS installation instead, and link the module
directory into WHMCS:

```bash
mkdir -p /path/to/whmcs-modules && cd /path/to/whmcs-modules
composer require actra/actra-whmcs-registrar
ln -s /path/to/whmcs-modules/vendor/actra/actra-whmcs-registrar/src/actra /path/to/whmcs/modules/registrars/actra
```

Update with `composer update actra/actra-whmcs-registrar` in that directory. If your hosting does not allow symbolic
links (e.g. `open_basedir`), copy the directory instead after every update:

```bash
cd /path/to/whmcs-modules/vendor/actra/actra-whmcs-registrar
rsync -a --delete src/actra/ /path/to/whmcs/modules/registrars/actra/
```

Read the [CHANGELOG](CHANGELOG.md) before updating: minor versions may require changes of the module settings.

### Manual Installation

1. Download the latest release.
2. Extract the files and upload the contents of the `src/actra` directory to your WHMCS installation at
   `modules/registrars/actra`.

## Configuration

1. Log in to your WHMCS Admin Area.
2. Navigate to **System Settings > Domain Registrars**.
3. Activate the **actra** module.
4. Enter the API URL (base URL of the Actra registrar API, ending with `/api/v1`), your API key and your customer
   number at Actra, and configure the available options.

## Development

The project follows the [Actra coding standard](https://github.com/Actra-AG/coding-standard) (see
[AGENTS.md](AGENTS.md)). Run the code style check, the static analysis and the tests with DDEV
(PHP 8.3, the PHP version supported by WHMCS):

```bash
ddev start
ddev composer install
ddev composer check
```

## Authors

- [Christof Moser](https://www.actra.ch/)

## License

This project is licensed under the MIT License – see the [LICENSE](LICENSE) file for details.

## Support

For support, please visit [https://www.actra.ch/](https://www.actra.ch/).
