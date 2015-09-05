<?php

namespace Tests\Unit\Modules\Api;

use Tests\TestCase;
use Mockery;

use Modules\Api\Formatter;

class FormatterTest extends TestCase
{
    public function testGetFormat()
    {
        $format = [
            'status'   => ':status',
            'response' => ':response',
        ];

        $formatter = new Formatter($format);

        $this->assertEquals($format, $formatter->getFormat());
    }

    public function testFormat()
    {
        $format = [
            'status'   => ':status',
            'response' => ':response',
        ];
        
        $values = [
            ':status'   => 100,
            ':response' => 'value',
        ];

        $formatted = [
            'status'   => 100,
            'response' => 'value',
        ];

        $formatter = new Formatter($format);

        $this->assertEquals($formatted, $formatter->format($values));
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
