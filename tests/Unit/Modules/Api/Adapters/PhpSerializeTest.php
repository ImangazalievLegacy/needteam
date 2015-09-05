<?php

namespace Tests\Unit\Modules\Api\Adapters;

use Tests\TestCase;
use Mockery;

use Modules\Api\Serializer\Adapter\PhpSerialize;

class PhpSerializeTest extends TestCase
{
    public function testSerialize()
    {
        $adapter = new PhpSerialize();

        $this->assertEquals(serialize(['key' => 'value']), $adapter->serialize(['key' => 'value']));
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
