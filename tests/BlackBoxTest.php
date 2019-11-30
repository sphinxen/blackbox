<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class BlackBoxTest extends TestCase
{
    /**
     * @dataProvider providerValidInputData
     */
    public function testValidInputData($query, $arguments, $expected): void
    {
        $_GET = $query;

        $expected = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$expected}";
        $actual = (new BlackBox())->url(...$arguments);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    /**
     * @dataProvider providerMissingQueryInputData
     */
    public function testInputWithMissingGetVariable($query, $arguments, $expected): void
    {
        $_GET = $query;

        $url = (new BlackBox())->url(...$arguments);

        $actual = parse_url($url, PHP_URL_QUERY);
        $expected = parse_url($expected, PHP_URL_QUERY);

        $this->assertEmpty($query);
        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    /**
     * @dataProvider providerValidInputData
     */
    public function testMissmatchingNumberOfArgumentsAndValues($query, $arguments, $expected): void
    {
        $_GET = array_merge($query, ['test' => true]);
        $arguments[0] = (array) $arguments[0] + ['test'];
        $expected .= '&test=1';

        $actual = parse_url((new BlackBox())->url(...$arguments), PHP_URL_QUERY);
        $expected = parse_url($expected, PHP_URL_QUERY);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected ".print_r($expected)." but resulted in " . print_r($actual)
        );
    }

    /**
     * @dataProvider providerMissingArgumentInput
     */
    public function testInputWithMissingArguments($query, $arguments, $expected): void
    {
        $_GET = $query;

        $expected = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$expected}";
        $actual = (new BlackBox())->url($arguments);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    /**
     * @dataProvider providerValidInputData
     */
    public function testRequestWithSSLConnection($query, $arguments, $expected): void
    {
        $_GET = $query;
        $_SERVER['HTTPS'] = true;

        $expected = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$expected}";
        $actual = (new BlackBox())->url(...$arguments);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    /**
     * @dataProvider providerValidInputData
     */
    public function testAdditionalQueryArguments($query, $arguments, $expected): void
    {
        $_GET = array_merge($query, ['test' => true]);
        $arguments[0] = (array) $arguments[0] + ['test'];
        $expected .= '&test=1';

        $url = (new BlackBox())->url(...$arguments);

        $actual = parse_url($url, PHP_URL_QUERY);
        $expected = parse_url($expected, PHP_URL_QUERY);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected ".print_r($expected)." but resulted in " . print_r($actual)
        );
    }

    /**
     * @dataProvider providerURLEncodedInput
     */
    public function testURLEncodedInputQueryData($query, $arguments, $expected)
    {
        $_GET = $query;

        $expected = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$expected}";
        $actual = (new BlackBox())->url(...$arguments);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    public function testNestedQueryInputData()
    {
        $_GET = [
            'list' => '%26test',
            'page' => 2,
            'biz' => [
                'foo' => 'bar',
                'baz' => '%264'
            ]
        ];

        $arguments = [
            ['list', 'page'],
            ['&test', 3]
        ];

        $expected = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?page=3&biz%5Bfoo%5D=bar&biz%5Bbaz%5D=%264";
        $actual = (new BlackBox())->url(...$arguments);

        $this->assertEquals(
            $expected,
            $actual,
            __FUNCTION__ . "Expected {$expected} but resulted in {$actual}"
        );
    }

    public function tearDown()
    {
        $_SERVER['HTTPS'] = false;
        parent::tearDown();
    }


    public function providerValidInputData()
    {
        return [
            [
                ['list' => 'customers', 'page' => 2],
                ['page', 3],
                'list=customers&page=3',
            ],
            [
                ['list' => 'customers', 'page' => 2],
                ['page', 2],
                'list=customers',
            ],
            [
                ['list' => 'customers', 'page' => 2],
                [['list','page'], ['employees', null]],
                'list=employees',
            ],
            [
                ['foo' => 1, 'bar' => 2],
                ['foo', 2, ['baz' => 3]],
                'foo=2&bar=2&baz=3',
            ],
            [
                ['foo' => 1, 'bar' => 2],
                [['foo', 'bar'], [false,1]],
                'bar=1',
            ],
            [
                ['foo' => 1, 'bar' => 2],
                ['baz'],
                'foo=1&bar=2'
            ]
        ];
    }

    public function providerMissingQueryInputData()
    {
        return [
            [
                [],
                ['page', 3],
                '',
            ],
            [
                false,
                ['page', 2],
                '',
            ],
            [
                0,
                [['list','page'], ['employees', null]],
                '',
            ],
            [
                null,
                ['foo', 2, ['baz' => 3]],
                'baz=3',
            ]
        ];
    }

    public function providerMissingArgumentInput()
    {
        return [
            [
                ['list' => 'customers', 'page' => 2],
                [],
                'list=customers&page=2',
            ],
            [
                ['list' => 'customers', 'page' => 2],
                false,
                'list=customers&page=2',
            ],
            [
                ['list' => 'customers', 'page' => 2],
                null,
                'list=customers&page=2',
            ],
            [
                ['foo' => 1, 'bar' => 2],
                0,
                'foo=1&bar=2',
            ]
        ];
    }

    public function providerURLEncodedInput()
    {
        return [
            [
                ['list' => '%26test', 'page' => 2, 'foo' => 'bar'],
                [['list', 'page'], ['&customer', 3]],
                'list=%26customer&page=3&foo=bar'
            ],
            [
                ['%24list' => 'test', 'page' => 2, 'foo' => 'bar'],
                [['$list', 'page'], ['&customer', 2]],
                '%24list=%26customer&foo=bar'
            ],
        ];
    }

}