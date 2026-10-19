# Changelog

## v0.7.0 - 2026-10-19

Requires the Actra registrar API v1.1.0 or newer, which Actra provides together with this release. Older module
versions do not work with this API version, and this module version does not work with older API versions.

### Fixed

- Registrations and contact changes of domains outside `.ch` and `.li` used contacts of the Switch registry. Contacts
  are now created without a registry; Actra creates them at the registry of the domain when needed.
- Changes of the admin and billing contacts in the contact details of a domain were ignored, although WHMCS showed
  them as saved. They are now saved like the registrant and the technical contact. An admin or billing contact is only
  changed if the domain has one, because some registries (e.g. Switch for `.ch` and `.li`) have no such contacts.
- Registrations and other domain changes failed with "Connection Error: 28 - Operation timed out" when the registry
  answered slowly: every request had a timeout of 10 seconds. Requests that query the registry (availability, host
  information) now wait up to 45 seconds, requests that change domains, hosts, DS records or auth codes up to 60
  seconds.

### Changed

- Contacts are created with the new contact API and are referenced by their public contact ID (`C-` and 12
  characters). Contacts with identical data are reused, so a registration or contact change no longer leaves a new
  contact behind.
- The functions of the module explain the contact errors of the API in the language of the user, followed by the
  details of the API: the registry declined the contact change (an administrator at Actra checks it, the contacts stay
  unchanged until then), a contact cannot be used (e.g. an invalid phone number or email address), the registrant
  change has a fee or a formal process at the registry, or the contact is unknown. Other errors of the API are shown as
  received.
- When the registry is not reachable or does not answer in time, every function of the module shows "The registry is
  currently not reachable. Please try again later." in the language of the user.
- Domains of TLDs that require an admin contact (e.g. `.com`) can be registered: the API uses the registrant for the
  contact roles the TLD requires.
- Child nameservers (registering, changing and deleting nameservers below a domain) no longer send the Switch
  registry: the API uses the registry of the registrar's own domain the nameserver belongs to, so child nameservers of
  domains at other registries work as well.
- The host information of a child nameserver no longer contains the registry object ID (`roid`), which the API no
  longer returns.
- Removed the calls of contact endpoints that the API no longer offers (`checkContact`, `updateContact`) or that the
  module did not use (`infoContact`, `deleteContact`).

## v0.6.1 - 2026-10-13

### Fixed

- The module logo is readable on the light backgrounds of the WHMCS admin area: the white lettering is shown on the
  navy background of the Actra website.

## v0.6.0 - 2026-10-13

### Added

- German and English texts: the registrar settings, the buttons, the DNSSEC page in the client area and the error
  messages of the module are shown in the language of the admin or client. Other languages fall back to English.

### Fixed

- The DNSSEC page in the client area shows the confirmation after adding or deleting a DS record.

### Changed

- The DNSSEC page in the client area was only available in German; it now follows the language of the client.

## v0.5.0 - 2026-10-13

### Added

- New required module setting **Customer Number**: the customer number at Actra billed for registered and transferred
  domains. The Actra registrar API requires it for domain registrations and transfers. Existing installations
  must enter it in **System Settings > Domain Registrars** before the next registration or transfer.

### Fixed

- Fixed saving nameservers and contact details with the current Actra registrar API, which expects the target
  state of the domain (complete nameserver list, contact handles) instead of the changes to apply.
- Fixed registering domains and saving nameservers with nameservers of other providers: the module no longer creates
  host objects for them, which the API only allows below the registrar's own domains.
- Fixed a fatal error when an API response does not contain an optional field, e.g. a contact of a domain.
- Fixed a fatal error when saving contact details of a domain without registrant data.
- API responses that are not JSON (e.g. the error page of a proxy) are reported with the API path and HTTP status
  and are written to the module log.

### Changed

- The ASCII domain name is read from the `asciiName` field of the API, for correct names of IDN domains.
- Error messages of the API include the error code reported by the API.
- Nameservers submitted by WHMCS are compared and sent in lowercase, without duplicates.

## v0.4.0 - 2026-09-06

### Fixed

- Fixed an unhandled match error when opening the domain details of a domain whose registry reports a status code
  outside the EPP standard vocabulary, such as `connect` for a newly registered `.de` domain.
- Fixed a fatal error during domain synchronization for domains without an expiry date.

### Changed

- Derived domain properties are read from the Actra registrar API instead of being interpreted from the raw
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
  parameters according to the Actra registrar API.
- Updated domain, contact, host, DNSSEC, and system API wrappers to use explicit GET, POST, PATCH, and DELETE requests.
- Removed the obsolete API username configuration field from the registrar settings.
- Updated API key validation to use bearer-token based validation without a username request parameter.

## v0.2.0 - 2026-08-10

### Changed

- Updated incoming domain transfers to submit WHMCS contact data directly to the Actra registrar API.
- Removed pre-transfer contact creation from the transfer flow.
- Removed registry-specific contact handling from incoming transfers.
- Added support for owner, admin, tech, and billing transfer contact data.
- Added optional nameserver submission during transfers.

## v0.1.2 - 2026-05-18

### Fixed

- Simplified the nameserver keys in the domain information returned to WHMCS.

## v0.1.1 - 2026-04-14

### Changed

- Improved the compatibility of the cURL requests and the robustness of the logging.

## v0.1.0 - 2026-04-04

### Added

- First version of the Actra registrar module for WHMCS, installable with Composer.
