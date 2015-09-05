<?php

namespace Tests\Unit\Modules\Api\Adapters;

use Tests\TestCase;
use Mockery;

use Modules\Api\Serializer\Adapter\Json;

class JsonTest extends TestCase
{
    public function testSerialize()
    {
        $adapter = new Json();

        $this->assertEquals(json_encode(['key' => 'value']), $adapter->serialize(['key' => 'value']));
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
