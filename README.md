# Actra WHMCS Registrar Module

Actra Registrar Module for WHMCS.

## Description

This module provides functionality for registering, transferring, and renewing domains through Actra's registrar API
within the WHMCS platform.

The module communicates with the Actra Domains REST API using bearer-token authentication and JSON requests.

Domain properties such as the registration status, the transfer lock, and the restorable state are read from the
normalized fields of the API response. The registry-specific status codes are therefore interpreted by the Actra Domains
API rather than by this module, which requires an API that provides the `normalizedStatus` field and the derived
boolean fields of the domain response.

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

## Transfer Behavior

Incoming domain transfers submit WHMCS contact data directly to the Actra Domains API. The Actra Domains backend
resolves the appropriate registry from the TLD and creates or reuses matching contacts automatically.

When available, separate WHMCS Admin, Tech, and Billing contact data is submitted for the transfer. If role-specific
contact data is incomplete or unavailable, the module falls back to the registrant contact data.

## Installation

### Via Composer (Recommended)

To install the module via composer, run:

```bash
composer require actra/actra-whmcs-registrar
```

After installation, you may need to move or symlink the module files to your WHMCS `modules/registrars/actra` directory.

### Manual Installation

1. Download the latest release.
2. Extract the files and upload the contents of the `src/actra` directory to your WHMCS installation at
   `modules/registrars/actra`.

## Configuration

1. Log in to your WHMCS Admin Area.
2. Navigate to **System Settings > Domain Registrars**.
3. Activate the **actra** module.
4. Enter your API credentials and configure the available options.

## Authors

- [Christof Moser](https://www.actra.ch/)

## License

This project is licensed under the MIT License – see the [LICENSE](LICENSE) file for details.

## Support

For support, please visit [https://www.actra.ch/](https://www.actra.ch/).
