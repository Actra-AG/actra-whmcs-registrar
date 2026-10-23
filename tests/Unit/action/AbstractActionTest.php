<?php

/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace WHMCS\Module\Registrar\Actra\tests\Unit\action;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use WHMCS\Module\Registrar\Actra\action\AbstractAction;
use WHMCS\Module\Registrar\Actra\api\ApiException;
use WHMCS\Module\Registrar\Actra\settings\ModuleSettings;

final class AbstractActionTest extends TestCase
{
    /**
     * @return iterable<string, array{Throwable, string}>
     */
    public static function errors(): iterable
    {
        yield 'API key not allowed' => [
            new ApiException(message: 'error', httpStatusCode: 403, errorCode: '1009', apiMessage: 'Forbidden'),
            'The API key of the module may not use this function of the Actra registrar API. Please check the API key '
                . 'in the settings of the registrar module or contact Actra.',
        ];
        yield 'rate limited with Retry-After' => [
            new ApiException(
                message: 'error',
                httpStatusCode: 429,
                errorCode: '1010',
                apiMessage: 'Too many registry checks',
                retryAfterSeconds: 42,
            ),
            'Too many availability checks with the API key of the module. Please try again in 42 seconds.',
        ];
        yield 'rate limited without Retry-After' => [
            new ApiException(message: 'error', httpStatusCode: 429, errorCode: '1010', apiMessage: 'Too many'),
            'Too many availability checks with the API key of the module. Please try again later.',
        ];
        yield 'other API error' => [
            new ApiException(message: 'API error as received', httpStatusCode: 400, errorCode: '2302', apiMessage: 'x'),
            'API error as received',
        ];
        yield 'other error' => [new RuntimeException(message: 'Connection Error'), 'Connection Error'];
    }

    #[DataProvider('errors')]
    public function testGetErrorMessage(Throwable $throwable, string $expectedMessage): void
    {
        $action = new class (params: [ModuleSettings::CONFIG_LOG => '']) extends AbstractAction {
            #[Override]
            protected function execute(): void {}

            public function errorMessage(Throwable $throwable): string
            {
                return $this->getErrorMessage(throwable: $throwable);
            }
        };

        $this->assertSame($expectedMessage, $action->errorMessage(throwable: $throwable));
    }
}
