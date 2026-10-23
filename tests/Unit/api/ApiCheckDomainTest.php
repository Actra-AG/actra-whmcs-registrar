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
use WHMCS\Module\Registrar\Actra\api\ApiCheckDomain;
use WHMCS\Module\Registrar\Actra\api\ApiClient;
use WHMCS\Module\Registrar\Actra\api\ApiRecord;

final class ApiCheckDomainTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, mixed>, bool, bool|null}>
     */
    public static function responses(): iterable
    {
        yield 'available, no premium domain' => [
            ['isAvailable' => true, 'isPremium' => false, 'premiumPrice' => null],
            true,
            false,
        ];
        yield 'available premium domain' => [
            ['isAvailable' => true, 'isPremium' => true, 'premiumPrice' => ['create' => '127.66']],
            true,
            true,
        ];
        yield 'registry does not tell' => [
            ['isAvailable' => true, 'isPremium' => null, 'premiumPrice' => null],
            true,
            null,
        ];
        yield 'API without premium fields' => [['isAvailable' => false], false, null];
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('responses')]
    public function testReadsAvailabilityAndPremiumFlag(array $data, bool $expectedAvailable, ?bool $expectedPremium): void
    {
        $apiClient = new class (data: $data) extends ApiClient {
            /**
             * @param array<string, mixed> $data
             */
            public function __construct(private readonly array $data)
            {
                parent::__construct(apiUrl: 'https://api.example.com/api/v1', apiKey: 'test', log: false);
            }

            #[Override]
            public function get(string $path, array $query = [], int $timeoutInSeconds = ApiClient::TIMEOUT_DEFAULT): ApiRecord
            {
                return new ApiRecord(fields: $this->data);
            }
        };

        $checkDomain = new ApiCheckDomain(apiClient: $apiClient, asciiDomainName: 'hotel.online');

        $this->assertSame($expectedAvailable, $checkDomain->isAvailable);
        $this->assertSame($expectedPremium, $checkDomain->isPremium);
    }
}
