# Actra WHMCS Registrar Module

Actra Registrar Module for WHMCS.

## Description

This module provides functionality for registering, transferring, and renewing domains through Actra's registrar API within the WHMCS platform.

## Features

- Register Domain
- Transfer Domain
- Renew Domain (where applicable)
- Get EPP Code
- Manage Nameservers
- Manage Contact Details
- DNSSEC Support

## Transfer Behavior

Incoming domain transfers submit WHMCS contact data directly to the Actra Domains API.
The Actra Domains backend resolves the appropriate registry from the TLD and creates or
reuses matching contacts automatically.

When available, separate WHMCS Admin, Tech, and Billing contact data is submitted for
the transfer. If role-specific contact data is incomplete or unavailable, the module
falls back to the registrant contact data.

## Installation

### Via Composer (Recommended)

To install the module via composer, run:

```bash
composer require actra/actra-whmcs-registrar
```

After installation, you may need to move or symlink the module files to your WHMCS `modules/registrars/actra` directory.

### Manual Installation

1. Download the latest release.
2. Extract the files and upload the contents of the `src/actra` directory to your WHMCS installation at `modules/registrars/actra`.

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
