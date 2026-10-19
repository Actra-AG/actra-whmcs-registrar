<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\api;

use RuntimeException;

/**
 * Unsuccessful answer of the Actra registrar API, with the HTTP status and the error code of the API.
 */
final class ApiException extends RuntimeException
{
    // updateDomain: the registry declined a change of contacts, an administrator at Actra checks it
    public const string ERROR_CODE_CONTACT_CHANGE_NEEDS_REVIEW = '1004';
    // A contact cannot be used (invalid phone or email, missing data of the TLD)
    public const string ERROR_CODE_CONTACT_NOT_USABLE = '1005';
    // updateDomain: the registrant change of the TLD must be accepted (fee or formal process)
    public const string ERROR_CODE_REGISTRANT_CHANGE_NOT_ACCEPTED = '1006';
    public const string ERROR_CODE_UNKNOWN_CONTACT = '1007';
    // HTTP 503: the registry did not answer (in time)
    public const string ERROR_CODE_REGISTRY_NOT_REACHABLE = '1008';

    /**
     * @param string $apiMessage error message of the API as received
     */
    public function __construct(
        string $message,
        public readonly int $httpStatusCode,
        public readonly string $errorCode,
        public readonly string $apiMessage,
    ) {
        parent::__construct(message: $message);
    }

    public function isContactChangeNeedsReview(): bool
    {
        return $this->httpStatusCode === 409 && $this->errorCode === ApiException::ERROR_CODE_CONTACT_CHANGE_NEEDS_REVIEW;
    }
}
