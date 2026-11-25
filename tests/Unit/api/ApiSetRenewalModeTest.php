<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\api;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\api\ApiRecord;
use WHMCS\Module\Registrar\Actra\api\ApiSetRenewalMode;
use WHMCS\Module\Registrar\Actra\data\RenewalModeEnum;

final class ApiSetRenewalModeTest extends TestCase
{
    /**
     * @return iterable<string, array{RenewalModeEnum, string|null}>
     */
    public static function renewalModes(): iterable
    {
        yield 'let expire' => [RenewalModeEnum::EXPIRE, '2027-03-14'];
        yield 'renew again' => [RenewalModeEnum::RENEW, null];
    }

    #[DataProvider('renewalModes')]
    public function testSendsRenewalModeAndReadsDeletionDate(
        RenewalModeEnum $renewalMode,
        ?string $deletionDate,
    ): void {
        $apiClient = new class (data: ['renewalMode' => $renewalMode->value, 'deletionDate' => $deletionDate]) extends ApiClient {
            /** @var array<string, mixed> */
            public array $request = [];

            /**
             * @param array<string, string|null> $data
             */
            public function __construct(private readonly array $data)
            {
                parent::__construct(apiUrl: 'https://api.example.com/api/v1', apiKey: 'test', log: false);
            }

            #[Override]
            public function patch(
                string $path,
                array $query = [],
                array $body = [],
                int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT,
            ): ApiRecord {
                $this->request = [
                    'path' => $path,
                    'query' => $query,
                    'body' => $body,
                    'timeoutInSeconds' => $timeoutInSeconds,
                ];

                return new ApiRecord(fields: $this->data);
            }
        };

        $setRenewalMode = new ApiSetRenewalMode(
            apiClient: $apiClient,
            asciiDomainName: 'example.ch',
            renewalMode: $renewalMode,
        );

        $this->assertSame(
            [
                'path' => 'setRenewalMode',
                'query' => ['domainName' => 'example.ch'],
                'body' => ['renewalMode' => $renewalMode->value],
                'timeoutInSeconds' => ApiClient::TIMEOUT_REGISTRY_CHANGE,
            ],
            $apiClient->request,
        );
        $this->assertSame($deletionDate, $setRenewalMode->deletionDate);
    }
}
