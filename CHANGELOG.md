# Changelog

## v0.4.0 - 2026-09-06

### Fixed

- Fixed an unhandled match error when opening the domain details of a domain whose registry reports a status code
  outside the EPP standard vocabulary, such as `connect` for a newly registered `.de` domain.
- Fixed a fatal error during domain synchronization for domains without an expiry date.

### Changed

- Derived domain properties are read from the Actra Domains REST API instead of being interpreted from the raw
  registry status codes. This requires an API that provides the `normalizedStatus`, `transferLock`, `deleteLock`,
  `updateLock`, `renewLock`, `delegated`, `pendingTransfer`, and `restorable` fields of the domain response.
- The transfer lock is read from the `transferLock` field of the API and is no longer derived from the first entry of
  the raw registry status array, so a lock is also recognized when the registry reports it in another position.
- The domain registration status shown in WHMCS now distinguishes suspended, expired, redemption, and pending states
  instead of only active and inactive. An unexpected status is reported as `unknown` instead of raising an error.
- The restorable state of a domain is reported from the API instead of always being reported as not restorable.
- Domain synchronization reports the domain status from the normalized API status instead of always reporting the
  domain as active. An unexpected status leaves the domain status in WHMCS unchanged.
- Domain synchronization keeps the expiry date known to WHMCS when the registry does not report one.
- Domain synchronization returns an error response instead of throwing when the API request fails.

## v0.3.2 - 2026-08-11

### Fixed

- Enabled IRTP handling for `.com` domains by matching WHMCS TLD values without a leading dot.

## v0.3.1 - 2026-08-11

### Fixed

- Fixed a runtime type error when the API returns nullable domain information fields.
- Fixed handling of domains without an expiry date in the domain information response.

## v0.3.0 - 2026-08-11

### Added

- Documented a known WHMCS 9.0.x admin-area issue where stale registrar configuration validation warnings can persist in
  the current admin session after a later successful validation.

### Changed

- Replaced the legacy form-data API request abstraction with explicit REST API requests using JSON bodies and query
  parameters according to the Actra Domains REST API.
- Updated domain, contact, host, DNSSEC, and system API wrappers to use explicit GET, POST, PATCH, and DELETE requests.
- Removed the obsolete API username configuration field from the registrar settings.
- Updated API key validation to use bearer-token based validation without a username request parameter.

## v0.2.0 - 2026-08-10

### Changed

- Updated incoming domain transfers to submit WHMCS contact data directly to the Actra Domains API.
- Removed pre-transfer contact creation from the transfer flow.
- Removed registry-specific contact handling from incoming transfers.
- Added support for owner, admin, tech, and billing transfer contact data.
- Added optional nameserver submission during transfers.

## v0.1.2 - 2026-05-18

## v0.1.1 - 2026-04-14

## v0.1.0 - 2026-04-04