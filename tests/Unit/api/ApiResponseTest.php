<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\api;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use WHMCS\Module\Registrar\Actra\api\ApiResponse;

final class ApiResponseTest extends TestCase
{
    public function testParseReturnsDataOfSuccessfulResponse(): void
    {
        $data = ApiResponse::parse(
            path: 'checkDomain',
            httpStatusCode: 200,
            body: '{"success":true,"data":{"isAvailable":true}}',
        );

        $this->assertTrue($data->getBool(key: 'isAvailable'));
    }

    public function testParseReturnsEmptyRecordForSuccessfulResponseWithoutData(): void
    {
        $data = ApiResponse::parse(path: 'deleteDomain', httpStatusCode: 200, body: '{"success":true}');

        $this->assertSame('', $data->getString(key: 'anything'));
    }

    /**
     * @return iterable<string, array{string, int, string, string}>
     */
    public static function failedResponses(): iterable
    {
        yield 'error with message and code' => [
            'createDomain',
            409,
            '{"success":false,"error":{"message":"Domain already exists","code":2302}}',
            'API request "createDomain" failed with HTTP 409: Domain already exists (code 2302)',
        ];
        yield 'error with code 0' => [
            'createDomain',
            400,
            '{"success":false,"error":{"message":"Invalid customerNumber value.","code":0}}',
            'API request "createDomain" failed with HTTP 400: Invalid customerNumber value.',
        ];
        yield 'error without message' => [
            'infoDomain',
            500,
            '{"success":false}',
            'API request "infoDomain" failed with HTTP 500: API request failed',
        ];
        yield 'success not true' => [
            'infoDomain',
            200,
            '{"success":"yes","data":{}}',
            'API request "infoDomain" failed with HTTP 200: API request failed',
        ];
        yield 'HTML page of a proxy' => [
            'infoDomain',
            502,
            '<html><body>Bad Gateway</body></html>',
            'API request "infoDomain" returned an invalid response with HTTP 502',
        ];
        yield 'empty body' => [
            'infoDomain',
            0,
            '',
            'API request "infoDomain" returned an invalid response with HTTP 0',
        ];
    }

    #[DataProvider('failedResponses')]
    public function testParseThrowsForFailedResponse(
        string $path,
        int $httpStatusCode,
        string $body,
        string $expectedMessage,
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($expectedMessage);

        ApiResponse::parse(path: $path, httpStatusCode: $httpStatusCode, body: $body);
    }
}
