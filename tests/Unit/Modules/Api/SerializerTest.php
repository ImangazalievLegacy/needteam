<?php

namespace Tests\Unit\Modules\Api;

use Tests\TestCase;
use Mockery;

use Modules\Api\Serializer;
use Modules\Api\Serializer\Adapter\Json;

class SerializerTest extends TestCase
{
    public function testSetDefaultAdapterName()
    {
        $serializer = new Serializer();

        $serializer->setDefaultAdapter('php-serialize');
        $this->assertEquals('php-serialize', $serializer->getDefaultAdapterName());
    }

    public function testGetDefaultAdapterName()
    {
        $serializer = new Serializer('json');

        $this->assertEquals('json', $serializer->getDefaultAdapterName());
    }

    public function testGetDefaultAdapter()
    {
        $serializer = new Serializer();

        $this->assertInstanceOf('Modules\Api\Serializer\Adapter\AdapterInterface', $serializer->getDefaultAdapter());
    }

    public function testInvalidAdapterNameException()
    {
        // if adapter name is not a string
        $this->setExpectedException('InvalidArgumentException');

        $serializer = new Serializer();
        $serializer->addAdapter([], 'Modules\Api\Serializer\Adapter\Json');
    }

    public function testInvalidAdapterException()
    {
        // if the specified class does not exist
        $this->setExpectedException('RuntimeException');

        $serializer = new Serializer();
        $serializer->addAdapter('adapter', 'adapter');
    }

    public function testAdapterNotFoundException()
    {
        // if the adapter does not exist
        $this->setExpectedException('RuntimeException');

        $serializer = new Serializer();
        $serializer->getAdapter('adapter');
    }

    public function testAddAdapter()
    {
        $serializer = new Serializer();

        // as string (the full name of the class)
        $serializer->addAdapter('adapter', 'Modules\Api\Serializer\Adapter\Json');
        
        $this->assertTrue($serializer->hasAdapter('adapter'));
        $this->assertInstanceOf('Modules\Api\Serializer\Adapter\Json', $serializer->getAdapter('adapter'));

        $adapter = new Json;

        // as object
        $serializer->addAdapter('adapter', $adapter);
        $this->assertSame($adapter, $serializer->getAdapter('adapter'));
    }

    public function testSerialize()
    {
        $data = ['key' => 'value'];

        $serializer = new Serializer();

        $adapterMock = Mockery::mock('Modules\Api\Serializer\Adapter\AdapterInterface')->shouldReceive('serialize')->with($data)->andReturn('serialized')->getMock();
        
        $serializer->addAdapter('adapter', $adapterMock);
        $serializer->setDefaultAdapter('adapter');

        $serialized = $serializer->serialize($data);
        $this->assertEquals('serialized', $serialized);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
