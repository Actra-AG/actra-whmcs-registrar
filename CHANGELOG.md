# Changelog

## v0.3.2

### Fixed

- Enabled IRTP handling for `.com` domains by matching WHMCS TLD values without a leading dot.

## v0.3.1

### Fixed

- Fixed a runtime type error when the API returns nullable domain information fields.
- Fixed handling of domains without an expiry date in the domain information response.

## v0.3.0

### Added

- Documented a known WHMCS 9.0.x admin-area issue where stale registrar configuration validation warnings can persist in
  the current admin session after a later successful validation.

### Changed

- Replaced the legacy form-data API request abstraction with explicit REST API requests using JSON bodies and query
  parameters according to the Actra Domains REST API.
- Updated domain, contact, host, DNSSEC, and system API wrappers to use explicit GET, POST, PATCH, and DELETE requests.
- Removed the obsolete API username configuration field from the registrar settings.
- Updated API key validation to use bearer-token based validation without a username request parameter.

## v0.2.0

### Changed

- Updated incoming domain transfers to submit WHMCS contact data directly to the Actra Domains API.
- Removed pre-transfer contact creation from the transfer flow.
- Removed registry-specific contact handling from incoming transfers.
- Added support for owner, admin, tech, and billing transfer contact data.
- Added optional nameserver submission during transfers.